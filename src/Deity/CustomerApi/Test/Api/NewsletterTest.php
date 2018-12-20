<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Test\Api;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Integration\Model\Oauth\Token as TokenModel;
use Magento\Newsletter\Model\Subscriber;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Helper\Customer as CustomerHelper;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class NewsletterTest
 * @package Deity\CustomerApi\Test\Api
 */
class NewsletterTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/customers/me/newsletter';

    const RESOURCE_PATH_CUSTOMER_TOKEN = "/V1/integration/customer/token";

    /**
     * @var Subscriber
     */
    protected $subscriber;

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

        $this->customerHelper = new CustomerHelper();
        $this->customerData = $this->customerHelper->createSampleCustomer();

        // get token
        $this->resetTokenForCustomerSampleData();

        $this->dataObjectProcessor = Bootstrap::getObjectManager()->create(
            \Magento\Framework\Reflection\DataObjectProcessor::class
        );

        $this->subscriber = Bootstrap::getObjectManager()->create(
            Subscriber::class
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
     * Tests if customer can be subscribed via endpoint
     */
    public function testSubscribe()
    {
        $customerData = $this->getCustomerData($this->customerData[CustomerInterface::ID]);
        $this->subscriber->unsubscribeCustomerById($customerData->getId());

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/subscribe',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
                'token' => $this->token,
            ],
        ];
        $this->assertTrue($this->_webApiCall($serviceInfo));
        $subscriber = $this->subscriber->loadByCustomerId($customerData->getId());
        $this->assertTrue($subscriber->isSubscribed());
    }

    /**
     * Tests if customer can be unsubscribed via endpoint
     */
    public function testUnSubscribe()
    {
        $customerData = $this->getCustomerData($this->customerData[CustomerInterface::ID]);
        $this->subscriber->subscribeCustomerById($customerData->getId());

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/unsubscribe',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
                'token' => $this->token,
            ],
        ];
        $this->assertTrue($this->_webApiCall($serviceInfo));
        $subscriber = $this->subscriber->loadByCustomerId($customerData->getId());
        $this->assertFalse($subscriber->isSubscribed());
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
