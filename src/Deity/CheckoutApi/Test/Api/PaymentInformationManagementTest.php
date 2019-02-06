<?php
declare(strict_types=1);

namespace Deity\CheckoutApi\Test\Api;

use Magento\Framework\App\Config;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class PaymentInformationManagementTest
 *
 * @package Deity\CheckoutApi\Test\Api
 */
class PaymentInformationManagementTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    const RESOURCE_PATH = '/V1/carts/mine/save-payment-information-and-order';

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

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
     * @magentoApiDataFixture Magento/Checkout/_files/quote_with_shipping_method.php
     */
    public function testPlaceOrderWithPaymentInfo()
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

        $paymenItnfo = [
            'payment_method' => [
                'method' => 'checkmo',
                'po_number' => null,
                'additional_data' => null
            ]
        ];

        $orderResponseObject = $this->_webApiCall($serviceInfo, $paymenItnfo);

        $this->assertArrayHasKey('order_id', $orderResponseObject, 'response expected to have order_id field');
        $this->assertArrayHasKey(
            'order_real_id',
            $orderResponseObject,
            'response expected to have real_order_id field'
        );
        $orderId = $orderResponseObject['order_id'];
        $orderRealId = $orderResponseObject['order_real_id'];

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class)->load($orderId);
        $items = $order->getAllItems();
        $this->assertCount(1, $items, 'order should have exactly one item');
        $this->assertEquals($orderRealId, $order->getIncrementId(), 'Order increment_id should match');
        $this->assertEquals('Simple Product', $items[0]->getName(), 'product name should match');
    }
}
