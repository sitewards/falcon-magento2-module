<?php
declare(strict_types=1);

namespace Deity\SalesApi\Test\Api;

use Magento\Framework\App\Config;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use Magento\Sales\Model\ResourceModel\Order;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GuestOrderManagementTest
 *
 * @package Deity\SalesApi\Test\Api
 */
class GuestOrderManagementTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    const RESOURCE_PATH_ORDER_INFO = '/V1/guest-orders/:orderId/order-info';

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
     * @magentoApiDataFixture ../../../../app/code/Deity/SalesApi/Test/_files/order_with_mask.php
     */
    public function testGetItem()
    {
        $this->_markTestAsRestOnly();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(
                    ':orderId',
                    $this->getFixtureOrderMaskedId(),
                    self::RESOURCE_PATH_ORDER_INFO
                ),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
        ];

        $orderInfo = $this->_webApiCall($serviceInfo, []);
        $this->assertArrayHasKey('items', $orderInfo, 'Response should contain items key');
        $this->assertEquals(1, count($orderInfo['items']), 'Exactly one product item should be returned');

        $this->assertEquals('customer@null.com', $orderInfo['customer_email'], 'Customer should match');

        $this->assertArrayHasKey('extension_attributes', $orderInfo);

        $extensionAttributes = $orderInfo['extension_attributes'];

        $requiredExtensionItems = ['masked_id', 'shipping_address', 'currency', 'shipping_assignments'];

        foreach ($requiredExtensionItems as $key) {
            $this->assertArrayHasKey($key, $extensionAttributes, "$key should be set");
        }
    }

    /**
     * Get fixture order masked id
     *
     * @return string
     */
    private function getFixtureOrderMaskedId() :string
    {
        /** @var  OrderInterface $orderModel */
        $orderModel = Bootstrap::getObjectManager()->create(
            OrderInterfaceFactory::class
        )->create();
        /** @var Order $resource */
        $resource = Bootstrap::getObjectManager()->create(
            Order::class
        );
        $resource->load($orderModel, '100000001', 'increment_id');

        return (string)$orderModel->getExtensionAttributes()->getMaskedId();
    }
}
