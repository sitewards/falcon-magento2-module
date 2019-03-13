<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Data\Express;

/**
 * Interface PaypalDataInterface
 *
 * @package Deity\PaypalApi\Api\Data\Express
 */
interface PaypalDataInterface
{
    const TOKEN = 'token';
    const URL = 'url';

    /**
     * Get Paypal token
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Get Paypal redirect url
     *
     * @return string
     */
    public function getUrl(): string;
}
