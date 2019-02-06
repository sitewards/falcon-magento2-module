<?php
declare(strict_types=1);

namespace Deity\Checkout\Model;

use Deity\CheckoutApi\Api\GuestPaymentInformationManagementInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterfaceFactory;
use Magento\Checkout\Api\GuestPaymentInformationManagementInterface as MagentoPaymentInformationManagementInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class GuestPaymentInformationManagement
 *
 * @package Deity\Checkout\Model
 */
class GuestPaymentInformationManagement implements GuestPaymentInformationManagementInterface
{
    /**
     * @var OrderResponseInterfaceFactory
     */
    private $orderResponseFactory;

    /**
     * @var MagentoPaymentInformationManagementInterface
     */
    private $paymentInformationManagement;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * PaymentInformationManagement constructor.
     * @param OrderResponseInterfaceFactory $orderResponseFactory
     * @param MagentoPaymentInformationManagementInterface $paymentInformationManagement
     * @param Session $checkoutSession
     */
    public function __construct(
        OrderResponseInterfaceFactory $orderResponseFactory,
        MagentoPaymentInformationManagementInterface $paymentInformationManagement,
        Session $checkoutSession
    ) {
        $this->orderResponseFactory = $orderResponseFactory;
        $this->paymentInformationManagement = $paymentInformationManagement;
        $this->checkoutSession = $checkoutSession;
    }

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
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ): OrderResponseInterface {
        $orderId = $this->paymentInformationManagement->savePaymentInformationAndPlaceOrder(
            $cartId,
            $email,
            $paymentMethod,
            $billingAddress
        );
        $orderRealId = $this->checkoutSession->getLastRealOrderId();

        return $this->orderResponseFactory->create([
            OrderResponseInterface::ORDER_ID => (string)$orderId,
            OrderResponseInterface::ORDER_REAL_ID => (string)$orderRealId
        ]);
    }
}
