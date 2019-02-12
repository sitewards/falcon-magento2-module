<?php
declare(strict_types=1);

namespace Deity\SalesApi\Api\Data;

/**
 * Interface OrderIdMaskInterface
 *
 * @package Deity\SalesApi\Api\Data
 */
interface OrderIdMaskInterface
{
    /**
     * Get masked id
     *
     * @return string
     */
    public function getMaskedId(): string;

    /**
     * Set masked id
     *
     * @param string $maskedId
     * @return OrderIdMaskInterface
     */
    public function setMaskedId(string $maskedId): OrderIdMaskInterface;

    /**
     * Get order id
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Set order id
     *
     * @param int $orderId
     * @return OrderIdMaskInterface
     */
    public function setOrderId(int $orderId): OrderIdMaskInterface;
}
