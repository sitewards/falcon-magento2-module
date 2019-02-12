<?php
declare(strict_types=1);

namespace Deity\Sales\Plugin\Api;

use Deity\SalesApi\Api\OrderIdMaskRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Psr\Log\LoggerInterface;

/**
 * Class OrderManagement
 *
 * @package Deity\Sales\Plugin\Api
 */
class OrderManagement
{
    /**
     * @var OrderIdMaskRepositoryInterface
     */
    private $orderIdMaskRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * OrderManagement constructor.
     * @param OrderIdMaskRepositoryInterface $orderIdMaskRepository
     * @param LoggerInterface $logger
     */
    public function __construct(OrderIdMaskRepositoryInterface $orderIdMaskRepository, LoggerInterface $logger)
    {
        $this->orderIdMaskRepository = $orderIdMaskRepository;
        $this->logger = $logger;
    }

    /**
     * After plugin for place function
     *
     * @param OrderManagementInterface $subject
     * @param OrderInterface $result
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPlace(OrderManagementInterface $subject, OrderInterface $result)
    {
        if (!$result->getCustomerId()) {
            $this->orderIdMaskRepository->create((int)$result->getEntityId());
        }
        return $result;
    }
}
