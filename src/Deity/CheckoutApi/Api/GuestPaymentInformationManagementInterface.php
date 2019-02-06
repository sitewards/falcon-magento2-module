<?php
declare(strict_types=1);

namespace Deity\CheckoutApi\Api;

use Deity\QuoteApi\Api\Data\OrderResponseInterface;

/**
 * Interface GuestPaymentInformationManagementInterface
 *
 * @package Deity\CheckoutApi\Api
 */
interface GuestPaymentInformationManagementInterface
{
    /**
     * Set payment information and place order for a specified cart.
     *
     * @param string $cartId
     * @param string $email
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface|null $billingAddress
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Deity\QuoteApi\Api\Data\OrderResponseInterface
     */
    public function savePaymentInformationAndPlaceOrder(
        $cartId,
        $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    ): OrderResponseInterface;
}
