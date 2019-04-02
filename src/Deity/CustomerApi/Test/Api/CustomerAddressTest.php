<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Test\Api;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Integration\Model\Oauth\Token as TokenModel;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Helper\Customer as CustomerHelper;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CustomerAddressTest
 * @package Deity\CustomerApi\Test\Api
 */
class CustomerAddressTest extends WebapiAbstract
{
    private const RESOURCE_PATH = '/V1/falcon/customers/me/address';

    private const RESOURCE_PATH_CUSTOMER_TOKEN = "/V1/integration/customer/token";

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var AccountManagementInterface
     */
    private $customerAccountManagement;

    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * @var TokenModel
     */
    private $token;

    /**
     * @var CustomerInterface
     */
    private $customerData;

    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * Execute per test initialization.
     */
    public function setUp()
    {
        $this->_markTestAsRestOnly();

        $this->customerRegistry = Bootstrap::getObjectManager()->get(
            \Magento\Customer\Model\CustomerRegistry::class
        );

        $this->customerRepository = Bootstrap::getObjectManager()->get(
            \Magento\Customer\Api\CustomerRepositoryInterface::class,
            ['customerRegistry' => $this->customerRegistry]
        );

        $this->customerAccountManagement = Bootstrap::getObjectManager()
            ->get(\Magento\Customer\Api\AccountManagementInterface::class);

        $this->customerHelper = new CustomerHelper();
        $this->customerData = $this->customerHelper->createSampleCustomer();

        // get token
        $this->resetTokenForCustomerSampleData();

        $this->dataObjectProcessor = Bootstrap::getObjectManager()->create(
            \Magento\Framework\Reflection\DataObjectProcessor::class
        );
    }

    /**
     * Ensure that fixture customer and his addresses are deleted.
     */
    public function tearDown()
    {
        if (isset($this->customerData[CustomerInterface::ID])) {
            /** @var \Magento\Framework\Registry $registry */
            $registry = Bootstrap::getObjectManager()->get(\Magento\Framework\Registry::class);
            $registry->unregister('isSecureArea');
            $registry->register('isSecureArea', true);
            $this->customerRepository->deleteById($this->customerData[CustomerInterface::ID]);
            $registry->unregister('isSecureArea');
            $registry->register('isSecureArea', false);
        }
        $this->customerRepository = null;
        parent::tearDown();
    }

    /**
     * Gets customer addresses
     */
    public function testGetAddresses()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
                'token' => $this->token,
            ],
        ];
        $addressessResponse = $this->_webApiCall($serviceInfo);
        $this->assertSame(2, $addressessResponse['total_count']);
        $this->assertSame(2, count($addressessResponse['items']));
        $this->assertArrayHasKey('default_shipping', $addressessResponse['items'][0]);
        $this->assertArrayHasKey('default_billing', $addressessResponse['items'][0]);
        $this->assertSame($this->customerData[CustomerInterface::ID], $addressessResponse['items'][0]['customer_id']);
        $this->assertSame($this->customerData[CustomerInterface::ID], $addressessResponse['items'][1]['customer_id']);
    }

    /**
     * Gets address by ID
     */
    public function testGetAddressById()
    {
        //Get expected details from the Service directly
        $customerData = $this->getCustomerData($this->customerData[CustomerInterface::ID]);
        $expectedCustomerDetails = $this->dataObjectProcessor->buildOutputDataArray(
            $customerData,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );
        $expectedCustomerDetails['addresses'][0]['id'] =
            (int)$expectedCustomerDetails['addresses'][0]['id'];

        $expectedCustomerDetails['addresses'][1]['id'] =
            (int)$expectedCustomerDetails['addresses'][1]['id'];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $expectedCustomerDetails['addresses'][0]['id'],
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
                'token' => $this->token,
            ],
        ];
        $addressResponse = $this->_webApiCall($serviceInfo);
        $this->assertEquals($expectedCustomerDetails['addresses'][0], $addressResponse);
    }

    /**
     * Tests if new address can be created
     */
    public function testCreateAddress()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH ,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
                'token' => $this->token,
            ],
        ];

        $requestData = ['address' => $this->getThirdFixtureAddressData()];
        $expected = $requestData['address'];
        $addressResponse = $this->_webApiCall($serviceInfo, $requestData);
        $addressResponse['id'] = $expected['id'];
        $expected['customer_id'] = $this->customerData[CustomerInterface::ID];
        $this->assertEquals($expected, $addressResponse);
    }

    /**
     * Tests if addresses can be deleted by ID
     */
    public function testAddressUpdate()
    {
        $customerData = $this->getCustomerData($this->customerData[CustomerInterface::ID]);
        $expectedCustomerDetails = $this->dataObjectProcessor->buildOutputDataArray(
            $customerData,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );

        $expectedCustomerDetails['addresses'][1]['id'] =
            (int)$expectedCustomerDetails['addresses'][1]['id'];

        $updatedAddress = $expectedCustomerDetails['addresses'][1];
        $updatedAddress['default_shipping'] = false;
        $updatedAddress['default_billing'] = false;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
                'token' => $this->token,
            ],
        ];

        // update
        $addressResponse = $this->_webApiCall($serviceInfo, ['address' => $updatedAddress]);
        $this->assertEquals($updatedAddress, $addressResponse);

        //retrieve and compare
        $customerData = $this->getCustomerData($this->customerData[CustomerInterface::ID]);
        $currentCustomerDetails = $this->dataObjectProcessor->buildOutputDataArray(
            $customerData,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );

        $currentCustomerDetails['addresses'][1]['id'] =
            (int)$currentCustomerDetails['addresses'][1]['id'];
        unset($updatedAddress['default_shipping']);
        unset($updatedAddress['default_billing']);
        $this->assertEquals($updatedAddress, $currentCustomerDetails['addresses'][1]);
    }

    /**
     * Tests if addresses can be deleted by ID
     */
    public function testAddressDelete()
    {
        $customerData = $this->getCustomerData($this->customerData[CustomerInterface::ID]);
        $expectedCustomerDetails = $this->dataObjectProcessor->buildOutputDataArray(
            $customerData,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );

        // Remove all
        foreach ($expectedCustomerDetails['addresses'] as $address) {
            $address['id'] = (int)$address['id'];
            $serviceInfo = [
                'rest' => [
                    'resourcePath' => self::RESOURCE_PATH . '/' . $address['id'],
                    'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_DELETE,
                    'token' => $this->token,
                ],
            ];
            $this->assertTrue($this->_webApiCall($serviceInfo));
        }

        // check if there are none
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
                'token' => $this->token,
            ],
        ];
        $addressessResponse = $this->_webApiCall($serviceInfo);
        $this->assertSame(0, $addressessResponse['total_count']);
        $this->assertSame(0, count($addressessResponse['items']));
    }

    /**
     * Return the customer details.
     *
     * @param int $customerId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    protected function getCustomerData($customerId)
    {
        $data = $this->customerRepository->getById($customerId);
        $this->customerRegistry->remove($customerId);
        return $data;
    }

    /**
     * Retrieve data of the first fixture address.
     *
     * @return array
     */
    protected function getFirstFixtureAddressData()
    {
        return [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'city' => 'CityM',
            'country_id' => 'US',
            'company' => 'CompanyName',
            'postcode' => '75477',
            'telephone' => '3468676',
            'street' => ['Green str, 67'],
            'id' => 1,
            'default_billing' => true,
            'default_shipping' => true,
            'customer_id' => '1',
            'region' => ['region' => 'Alabama', 'region_id' => 1, 'region_code' => 'AL'],
            'region_id' => 1,
        ];
    }

    /**
     * Retrieve data of the second fixture address.
     *
     * @return array
     */
    protected function getSecondFixtureAddressData()
    {
        return [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'city' => 'CityX',
            'country_id' => 'US',
            'postcode' => '47676',
            'telephone' => '3234676',
            'street' => ['Black str, 48'],
            'id' => 2,
            'default_billing' => false,
            'default_shipping' => false,
            'customer_id' => '1',
            'region' => ['region' => 'Alabama', 'region_id' => 1, 'region_code' => 'AL'],
            'region_id' => 1,
        ];
    }

    /**
     * Retrieve data of the second fixture address.
     *
     * @return array
     */
    protected function getThirdFixtureAddressData()
    {
        return [
            'firstname' => 'Joe',
            'lastname' => 'Doe',
            'city' => 'New City',
            'country_id' => 'US',
            'postcode' => (string)rand(50000, 60000),
            'telephone' => (string)rand(1000000, 9000000),
            'street' => ['White str, ' . (string)rand(1, 50)],
            'id' => 3,
            'default_billing' => false,
            'default_shipping' => false,
            'region' => ['region' => 'Alaska', 'region_id' => 2, 'region_code' => 'AK'],
            'region_id' => 2,
        ];
    }

    /**
     * Sets the test's access token for the customer fixture
     */
    protected function resetTokenForCustomerFixture()
    {
        $this->resetTokenForCustomer('customer@example.com', 'password');
    }

    /**
     * Sets the test's access token for the created customer sample data
     */
    protected function resetTokenForCustomerSampleData()
    {
        $this->resetTokenForCustomer($this->customerData[CustomerInterface::EMAIL], 'test@123');
    }

    /**
     * Sets the test's access token for a particular username and password.
     *
     * @param string $username
     * @param string $password
     */
    protected function resetTokenForCustomer($username, $password)
    {
        // get customer ID token
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH_CUSTOMER_TOKEN,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
        ];
        $requestData = ['username' => $username, 'password' => $password];
        $this->token = $this->_webApiCall($serviceInfo, $requestData);
    }
}
