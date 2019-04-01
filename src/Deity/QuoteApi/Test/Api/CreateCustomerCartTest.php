<?php
declare(strict_types=1);

namespace Deity\QuoteApi\Test\Api;

use Magento\Framework\App\Config;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CreateCustomerCartTest
 *
 * @package Deity\QuoteApi\Test\Api
 */
class CreateCustomerCartTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    private const RESOURCE_PATH = '/V1/falcon/carts/mine';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     *  setup before every test run. Update app config
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $appConfig = $this->objectManager->get(Config::class);
        $appConfig->clean();
    }

    /**
     * @magentoApiDataFixture Magento/Customer/_files/customer.php
     */
    public function testExecute()
    {
        $this->_markTestAsRestOnly();

        // get customer ID token
        /** @var \Magento\Integration\Api\CustomerTokenServiceInterface $customerTokenService */
        $customerTokenService = $this->objectManager->create(
            \Magento\Integration\Api\CustomerTokenServiceInterface::class
        );
        $token = $customerTokenService->createCustomerAccessToken('customer@example.com', 'password');

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
                'token' => $token
            ],
        ];

        $cartId = $this->_webApiCall($serviceInfo, []);

        $this->assertNotEmpty($cartId, 'Cart id should not be empty');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class)->load($cartId);

        $quoteAddress = $quote->getShippingAddress();

        $this->assertNotEmpty($quoteAddress->getId(), 'Quote shipping address should be already created');
    }
}
