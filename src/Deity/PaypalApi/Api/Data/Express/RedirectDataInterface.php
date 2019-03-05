<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Data\Express;

/**
 * Interface RedirectDataInterface
 *
 * @package Deity\PaypalApi\Api\Data\Express
 */
interface RedirectDataInterface
{
    const REDIRECT_FIELD = 'redirect';

    /**
     * Get Redirect URL
     *
     * @return string
     */
    public function getRedirect(): string;
}
