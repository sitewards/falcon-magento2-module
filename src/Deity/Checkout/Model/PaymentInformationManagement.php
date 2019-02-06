<?php
declare(strict_types=1);

namespace Deity\Checkout\Model;

use Deity\CheckoutApi\Api\PaymentInformationManagementInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterfaceFactory;
use Magento\Checkout\Api\PaymentInformationManagementInterface as MagentoPaymentInformationManagementInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class PaymentInformationManagement
 *
 * @package Deity\Checkout\Model
 */
class PaymentInformationManagement implements PaymentInformationManagementInterface
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
    ): OrderResponseInterface {
        $orderId = $this->paymentInformationManagement->savePaymentInformationAndPlaceOrder(
            $cartId,
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
