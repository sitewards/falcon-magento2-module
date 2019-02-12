<?php
declare(strict_types=1);

namespace Deity\Sales\Model;

use Deity\SalesApi\Api\Data\OrderIdMaskInterfaceFactory;
use Deity\Sales\Model\Order\ReadHandler as OrderExtension;
use Deity\Sales\Model\Order\Item\ReadHandler as OrderItemExtension;
use Deity\SalesApi\Api\GuestOrderManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class GuestOrderManagement
 *
 * @package Deity\Sales\Model
 */
class GuestOrderManagement implements GuestOrderManagementInterface
{

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderIdMaskFactory */
    private $orderIdMaskFactory;

    /** @var OrderItemExtension */
    private $orderItemExtension;

    /** @var OrderExtension */
    private $orderExtension;

    /**
     * GuestOrderManagement constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderExtension $orderExtension
     * @param OrderItemExtension $orderItemExtension
     * @param OrderIdMaskFactory $orderIdMaskFactory
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderExtension $orderExtension,
        OrderItemExtension $orderItemExtension,
        OrderIdMaskFactory $orderIdMaskFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderIdMaskFactory = $orderIdMaskFactory;
        $this->orderItemExtension = $orderItemExtension;
        $this->orderExtension = $orderExtension;
    }

    /**
     * Get Item
     *
     * @param string $orderId
     * @return OrderInterface
     * @throws NoSuchEntityException
     */
    public function getItem(string $orderId): OrderInterface
    {
        $orderIdMask = $this->orderIdMaskFactory->create()->load($orderId, 'masked_id');
        $realOrderId = $orderIdMask->getOrderId();
        if (!$realOrderId) {
            throw new NoSuchEntityException();
        }

        $order = $this->orderRepository->get($realOrderId);
        $this->orderExtension->execute($order);
        $this->addOrderItemExtensionAttributes($order);
        if (!$order->getId() || $order->getCustomerId()) {
            throw new NoSuchEntityException(__('Unable to find order %maskedOrderId', ['maskedOrderId' => $orderId]));
        }

        return $order;
    }

    /**
     * Add order item extension attributes
     *
     * @param OrderInterface $order
     */
    private function addOrderItemExtensionAttributes(OrderInterface $order)
    {
        foreach ($order->getItems() as $item) { /** @var OrderItemInterface $item */
            $this->orderItemExtension->execute($item);
        }
    }
}
