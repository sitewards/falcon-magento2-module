<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api;

use Deity\PaypalApi\Api\Data\PaypalDataInterface;

/**
 * Interface PaypalInterface
 *
 * @package Deity\PaypalApi\Api
 */
interface PaypalInterface
{
    /**
     * Get Token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\PaypalDataInterface
     */
    public function getToken(string $cartId): PaypalDataInterface;
}
