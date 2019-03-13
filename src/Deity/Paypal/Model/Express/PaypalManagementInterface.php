<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Paypal\Model\Express\Checkout;

/**
 * Class PaypalManagementInterface
 *
 * @package Deity\Paypal\Model\Express
 */
interface PaypalManagementInterface
{

    /**
     * Create paypal token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\PaypalDataInterface
     */
    public function createPaypalData(string $cartId): PaypalDataInterface;

    /**
     * Get Checkout object by cart ID
     *
     * @param string $cartId
     * @return Checkout
     */
    public function getExpressCheckout(string $cartId): Checkout;

    /**
     * Validate token for given cart
     *
     * @param string $cartId
     * @param string $token
     * @return bool
     * @throws LocalizedException
     */
    public function validateToken(string $cartId, string $token): bool;

    /**
     * Unset paypal token
     *
     * @param string $cartId
     * @throws LocalizedException
     */
    public function unsetToken(string $cartId): void;
}
