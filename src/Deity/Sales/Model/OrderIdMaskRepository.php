<?php
declare(strict_types=1);

namespace Deity\Sales\Model;

use Deity\Sales\Model\ResourceModel\OrderIdMask;
use Deity\SalesApi\Api\Data\OrderIdMaskInterface;
use Deity\SalesApi\Api\Data\OrderIdMaskInterfaceFactory;
use Deity\SalesApi\Api\OrderIdMaskRepositoryInterface;
use Magento\Framework\Math\Random;
use Psr\Log\LoggerInterface;

/**
 * Class OrderIdMaskRepository
 *
 * @package Deity\Sales\Model
 */
class OrderIdMaskRepository implements OrderIdMaskRepositoryInterface
{

    /**
     * @var Random
     */
    private $randomDataGenerator;

    /** @var OrderIdMaskInterfaceFactory */
    private $orderIdMaskFactory;

    /**
     * @var OrderIdMask
     */
    private $orderIdMaskResource;

    /** @var LoggerInterface */
    private $logger;

    /**
     * OrderIdMaskRepository constructor.
     * @param Random $randomDataGenerator
     * @param OrderIdMaskInterfaceFactory $orderIdMaskFactory
     * @param OrderIdMask $orderIdMaskResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        Random $randomDataGenerator,
        OrderIdMaskInterfaceFactory $orderIdMaskFactory,
        OrderIdMask $orderIdMaskResource,
        LoggerInterface $logger
    ) {
        $this->randomDataGenerator = $randomDataGenerator;
        $this->orderIdMaskFactory = $orderIdMaskFactory;
        $this->orderIdMaskResource = $orderIdMaskResource;
        $this->logger = $logger;
    }

    /**
     * Create order mask for given order id
     *
     * @param int $orderId
     * @return OrderIdMaskInterface
     */
    public function create(int $orderId): OrderIdMaskInterface
    {
        try {
            /** @var OrderIdMaskInterface $orderIdMask */
            $orderIdMask = $this->orderIdMaskFactory->create();
            $orderIdMask->setOrderId($orderId);
            $orderIdMask->setMaskedId($this->randomDataGenerator->getUniqueHash());

            $this->orderIdMaskResource->save($orderIdMask);
        } catch (\Exception $e) {
            //order is already saved so do not escalate this exception to not break ordering process
            $this->logger->critical($e->getMessage());
        }

        return $orderIdMask;
    }

    /**
     * Get order mask object for given order id
     *
     * @param int $orderId
     * @return OrderIdMaskInterface
     */
    public function get(int $orderId): OrderIdMaskInterface
    {
        /** @var OrderIdMaskInterface $orderIdMask */
        $orderIdMask = $this->orderIdMaskFactory->create();
        $this->orderIdMaskResource->load($orderIdMask, $orderId, 'order_id');

        return $orderIdMask;
    }
}
