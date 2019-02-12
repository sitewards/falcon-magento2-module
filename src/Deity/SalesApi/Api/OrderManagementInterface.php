<?php
declare(strict_types=1);

namespace Deity\SalesApi\Api;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;

/**
 * Interface OrderManagementInterface
 *
 * @package Deity\SalesApi\Api
 */
interface OrderManagementInterface
{
    /**
     * Get item
     *
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getItem(int $orderId): OrderInterface;

    /**
     * Get customer orders
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getCustomerOrders(
        \Magento\Framework\Api\SearchCriteria $searchCriteria
    ): OrderSearchResultInterface;
}
