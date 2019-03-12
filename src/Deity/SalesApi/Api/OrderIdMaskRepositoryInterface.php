<?php
declare(strict_types=1);

namespace Deity\SalesApi\Api;

use Deity\SalesApi\Api\Data\OrderIdMaskInterface;

/**
 * Interface OrderIdMaskRepositoryInterface
 *
 * @package Deity\SalesApi\Api
 */
interface OrderIdMaskRepositoryInterface
{

    /**
     * Create order mask for given order id
     *
     * @param int $orderId
     * @return OrderIdMaskInterface
     */
    public function create(int $orderId): OrderIdMaskInterface;

    /**
     * Get order mask object for given order id
     *
     * @param int $orderId
     * @return OrderIdMaskInterface
     */
    public function get(int $orderId): OrderIdMaskInterface;

    /**
     * Get order mask object by given mask id
     *
     * @param string $maskedId
     * @return OrderIdMaskInterface
     */
    public function getByMaskedOrderId(string $maskedId): OrderIdMaskInterface;
}
