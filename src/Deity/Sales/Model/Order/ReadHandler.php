<?php
declare(strict_types=1);

namespace Deity\Sales\Model\Order;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\ShippingAssignmentBuilder;

/**
 * Class ReadHandler
 *
 * @package Deity\Sales\Model\Order
 */
class ReadHandler implements ExtensionInterface
{

    /** @var ExtensionAttributesFactory */
    private $extensionAttributesFactory;

    /** @var ShippingAssignmentBuilder */
    private $shippingAssignmentBuilder;

    /** @var PriceCurrencyInterface */
    private $priceCurrency;

    /**
     * Extension constructor.
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param ShippingAssignmentBuilder $shippingAssignmentBuilder
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        ExtensionAttributesFactory $extensionAttributesFactory,
        ShippingAssignmentBuilder $shippingAssignmentBuilder,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
        $this->shippingAssignmentBuilder = $shippingAssignmentBuilder;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param OrderInterface $order
     * @param array $arguments
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($order, $arguments = [])
    {
        $extensionAttributes = $this->getOrderExtensionAttribute($order);

        $orderCurrency = $this->priceCurrency->getCurrencySymbol($order->getStoreId(), $order->getOrderCurrencyCode());
        $extensionAttributes->setCurrency($orderCurrency ?: $order->getOrderCurrencyCode());

        if (!$order->getIsVirtual()) {
            $extensionAttributes->setShippingAddress($order->getShippingAddress());
        }

        if (!$extensionAttributes->getShippingAssignments()) {
            /** @var ShippingAssignmentBuilder $shippingAssignment */
            $shippingAssignments = $this->shippingAssignmentBuilder;
            $shippingAssignments->setOrderId($order->getEntityId());
            $extensionAttributes->setShippingAssignments($shippingAssignments->create());
        }
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get order extension attribute
     *
     * @param OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderExtensionInterface|null|object
     */
    private function getOrderExtensionAttribute(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(OrderInterface::class);
        }

        return $extensionAttributes;
    }
}
