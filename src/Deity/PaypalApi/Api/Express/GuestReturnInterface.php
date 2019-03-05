<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Express;

use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;

/**
 * Interface GuestPaypalReturnInterface
 *
 * @package Deity\PaypalApi\Api\Express
 */
interface GuestReturnInterface
{
    /**
     * Process return from paypal gateway
     *
     * @param string $cartId
     * @param string $token
     * @param string $payerId
     * @return \Deity\PaypalApi\Api\Data\Express\RedirectDataInterface
     */
    public function processReturn(string $cartId, string $token, string $payerId): RedirectDataInterface;
}
