<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;

/**
 * Interface GuestTokenProviderInterface
 *
 * @package Deity\PaypalApi\Api\Express
 */
interface GuestTokenProviderInterface
{
    /**
     * Get token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\PaypalDataInterface
     */
    public function getToken(string $cartId): PaypalDataInterface;
}
