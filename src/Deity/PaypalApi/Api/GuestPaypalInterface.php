<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api;

use Deity\PaypalApi\Api\Data\PaypalDataInterface;

/**
 * Interface GuestPaypalInterface
 *
 * @package Deity\PaypalApi\Api
 */
interface GuestPaypalInterface
{
    /**
     * Get token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\PaypalDataInterface
     */
    public function getToken(string $cartId): PaypalDataInterface;
}
