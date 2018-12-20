<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Test\Api;

use Magento\Customer\Api\Data\CustomerInterface as Customer;
use Magento\Security\Model\Config;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Helper\Customer as CustomerHelper;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class PasswordResetTest
 * @package Deity\CustomerApi\Test\Api
 */
class PasswordResetTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/customers';

    const RESOURCE_PATH_CUSTOMER_TOKEN = "/V1/integration/customer/token";

    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * @var array
     */
    private $currentCustomerId;

    /**
     * @var \Magento\Config\Model\Config
     */
    private $config;

    /**
     * @var int
     */
    private $configValue;

    /**
     * Execute per test initialization.
     */
    public function setUp()
    {
        $this->customerHelper = Bootstrap::getObjectManager()->create(
            CustomerHelper::class
        );

        $this->config = Bootstrap::getObjectManager()->create(
            \Magento\Config\Model\Config::class
        );

        if ($this->config->getConfigDataValue(
            Config::XML_PATH_FRONTEND_AREA .
                Config::XML_PATH_PASSWORD_RESET_PROTECTION_TYPE
        ) != 0) {
            $this->configValue = $this->config
                ->getConfigDataValue(
                    Config::XML_PATH_FRONTEND_AREA .
                    Config::XML_PATH_PASSWORD_RESET_PROTECTION_TYPE
                );
            $this->config->setDataByPath(
                Config::XML_PATH_FRONTEND_AREA . Config::XML_PATH_PASSWORD_RESET_PROTECTION_TYPE,
                0
            );
            $this->config->save();
        }
    }

    /**
     * @throws \Exception
     */
    public function tearDown()
    {
        if (!empty($this->currentCustomerId) && is_array($this->currentCustomerId)) {
            foreach ($this->currentCustomerId as $customerId) {
                $serviceInfo = [
                    'rest' => [
                        'resourcePath' => self::RESOURCE_PATH . '/' . $customerId,
                        'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_DELETE,
                    ]
                ];

                $response = $this->_webApiCall($serviceInfo, ['customerId' => $customerId]);

                $this->assertTrue($response);
            }
        }
        $this->config->setDataByPath(
            Config::XML_PATH_FRONTEND_AREA . Config::XML_PATH_PASSWORD_RESET_PROTECTION_TYPE,
            $this->configValue
        );
        $this->config->save();
    }

    /**
     *
     */
    public function testPasswordReset()
    {
        $customerData = $this->createCustomer();

        $resetToken = substr(md5((string)mt_rand()), 0, 25);
        $password = uniqid('psw') . 'ABCD$%&!';

        $this->setResetPasswordData($resetToken, 'Y-m-d H:i:s', $customerData['id']);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/password/reset',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
            ],
        ];
        $requestData = [
            'email' => $customerData[Customer::EMAIL],
            'resetToken' => $resetToken,
            'newPassword' => $password
        ];
        $response = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertTrue($response);

        // get customer ID token to prove that new password works
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH_CUSTOMER_TOKEN,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
        ];
        $requestData = ['username' => $customerData[Customer::EMAIL], 'password' => $password];
        $token = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertNotEmpty($token);
    }

    /**
     * @return array
     */
    protected function createCustomer()
    {
        $customerData = $this->customerHelper->createSampleCustomer();
        $this->currentCustomerId[] = $customerData['id'];
        return $customerData;
    }

    /**
     * Set Rp data to Customer in fixture
     *
     * @param $resetToken
     * @param $date
     * @param int $customerIdFromFixture Which customer to use.
     * @throws \Exception
     */
    protected function setResetPasswordData(
        $resetToken,
        $date,
        int $customerIdFromFixture = 1
    ) {
        /** @var \Magento\Customer\Model\Customer $customerModel */
        $customerModel = Bootstrap::getObjectManager()->create(\Magento\Customer\Model\Customer::class);
        $customerModel->load($customerIdFromFixture);
        $customerModel->setRpToken($resetToken);
        $customerModel->setRpTokenCreatedAt(date($date));
        $customerModel->save();
    }
}
