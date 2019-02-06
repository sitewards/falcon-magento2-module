<?php
declare(strict_types=1);

namespace Deity\QuoteApi\Api;

use Deity\QuoteApi\Api\Data\OrderResponseInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Interface CartManagementInterface
 *
 * @api
 */
interface GuestCartManagementInterface
{
    /**
     * Places an order for a specified cart.
     *
     * @param string $cartId The cart ID.
     * @param PaymentInterface|null $paymentMethod
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Deity\QuoteApi\Api\Data\OrderResponseInterface
     */
    public function placeOrder($cartId, PaymentInterface $paymentMethod = null): OrderResponseInterface;
}
