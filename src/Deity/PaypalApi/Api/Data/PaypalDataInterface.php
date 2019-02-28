<?php
declare(strict_types=1);

namespace Deity\PaypalApi\Api\Data;

/**
 * Interface PaypalDataInterface
 *
 * @package Deity\PaypalApi\Api\Data
 */
interface PaypalDataInterface
{
    const TOKEN = 'token';
    const URL = 'url';
    const ERROR = 'error';

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

    /**
     * Get Paypal error
     *
     * @return string
     */
    public function getError(): string;
}
