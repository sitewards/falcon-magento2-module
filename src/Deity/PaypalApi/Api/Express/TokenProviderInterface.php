<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;

/**
 * Interface PaypalInterface
 *
 * @package Deity\PaypalApi\Api\Express
 */
interface TokenProviderInterface
{
    /**
     * Get Token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\PaypalDataInterface
     */
    public function getToken(string $cartId): PaypalDataInterface;
}
