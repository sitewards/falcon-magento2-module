<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Express;

use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;

/**
 * Interface GuestCancelInterface
 *
 * @package Deity\PaypalApi\Api\Express
 */
interface GuestCancelInterface
{
    /**
     * Process customer cancel scenario
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\RedirectDataInterface
     */
    public function processCancel(string $cartId): RedirectDataInterface;
}
