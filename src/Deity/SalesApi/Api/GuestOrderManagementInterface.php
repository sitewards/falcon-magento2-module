<?php
declare(strict_types=1);

namespace Deity\SalesApi\Api;

use Magento\Sales\Api\Data\OrderInterface;

/**
 * Interface GuestOrderManagementInterface
 *
 * @package Deity\SalesApi\Api
 */
interface GuestOrderManagementInterface
{
    /**
     * Get item
     *
     * @param string $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getItem(string $orderId): OrderInterface;
}
