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
    const REDIRECT_FIELD = 'redirectUrl';
    const ORDER_ID_FIELD = 'orderId';
    const REAL_ORDER_ID_FIELD = 'realOrderId';
    const UENC_FIELD = 'uenc';

    /**
     * Get Redirect URL
     *
     * @return string
     */
    public function getRedirect(): string;

    /**
     * Get order Id
     *
     * @return string
     */
    public function getOrderId(): string;

    /**
     * Get Order increment Id
     *
     * @return string
     */
    public function getRealOrderId(): string;

    /**
     * Get encoded payment message
     *
     * @return string
     */
    public function getUenc(): string;
}
