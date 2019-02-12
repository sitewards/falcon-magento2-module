<?php
declare(strict_types=1);

namespace Deity\Sales\Observer\Order;

use Deity\SalesApi\Api\Data\OrderIdMaskInterface;
use Deity\SalesApi\Api\OrderIdMaskRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class SetMaskedId
 *
 * @package Deity\Sales\Observer\Order
 */
class SetMaskedId implements ObserverInterface
{
    /**
     * @var OrderIdMaskRepositoryInterface
     */
    private $orderIdMaskRepository;

    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * SetMaskedId constructor.
     * @param OrderIdMaskRepositoryInterface $orderIdMaskRepository
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderIdMaskRepositoryInterface $orderIdMaskRepository,
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->orderIdMaskRepository = $orderIdMaskRepository;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * Execute Observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();

        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        if ($order->getCustomerId()) {
            $extensionAttributes->setMaskedId(0);
            return;
        }

        if (!$extensionAttributes->getMaskedId()) {
            $extensionAttributes->setMaskedId($this->getMaskedOrderId((int)$order->getEntityId()));
            $order->setExtensionAttributes($extensionAttributes);
        }
    }

    /**
     * Get masked order id
     *
     * @param int $orderId
     * @return string
     */
    private function getMaskedOrderId(int $orderId): string
    {
        /** @var OrderIdMaskInterface $orderIdMask */
        $orderIdMask = $this->orderIdMaskRepository->get($orderId);
        return $orderIdMask->getMaskedId();
    }
}
