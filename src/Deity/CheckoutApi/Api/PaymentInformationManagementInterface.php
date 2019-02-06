<?php
declare(strict_types=1);

namespace Deity\CheckoutApi\Api;

use Deity\QuoteApi\Api\Data\OrderResponseInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Interface PaymentInformationManagementInterface
 *
 * @package Deity\CheckoutApi\Api
 */
interface PaymentInformationManagementInterface
{
    /**
     * Set payment information and place order for a specified cart.
     *
     * @param int $cartId
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Deity\QuoteApi\Api\Data\OrderResponseInterface
     */
    public function savePaymentInformationAndPlaceOrder(
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ): OrderResponseInterface;
}
