<?php
declare(strict_types=1);

namespace Deity\MagentoApi\Api\Sales;

/**
 * Interface OrderManagement
 * @package Deity\MagentoApi\Api\Sales
 */
interface OrderManagementInterface
{
    /**
     * Get order_id from paypal hash
     *
     * @param string $paypalHash
     * @return int
     */
    public function getOrderIdFromHash(string $paypalHash): int;
}
