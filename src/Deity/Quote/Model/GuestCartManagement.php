<?php
declare(strict_types=1);

namespace Deity\Quote\Model;

use Deity\QuoteApi\Api\Data\OrderResponseInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterfaceFactory;
use Deity\QuoteApi\Api\GuestCartManagementInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\GuestCartManagementInterface as MagentoGuestCartManagementInterface;

/**
 * Class GuestCartManagement
 *
 * @package Deity\Quote\Model
 */
class GuestCartManagement implements GuestCartManagementInterface
{

    /**
     * @var MagentoGuestCartManagementInterface
     */
    private $guestQuoteManagement;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var OrderResponseInterfaceFactory
     */
    private $orderResponseFactory;

    /**
     * CartManagement constructor.
     * @param MagentoGuestCartManagementInterface $quoteManagement
     * @param Session $checkoutSession
     * @param OrderResponseInterfaceFactory $orderResponseFactory
     */
    public function __construct(
        MagentoGuestCartManagementInterface $quoteManagement,
        Session $checkoutSession,
        OrderResponseInterfaceFactory $orderResponseFactory
    ) {
        $this->orderResponseFactory = $orderResponseFactory;
        $this->checkoutSession = $checkoutSession;
        $this->guestQuoteManagement = $quoteManagement;
    }

    /**
     * Places an order for a specified cart.
     *
     * @param string $cartId The cart ID.
     * @param PaymentInterface|null $paymentMethod
     * @return \Deity\QuoteApi\Api\Data\OrderResponseInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function placeOrder($cartId, PaymentInterface $paymentMethod = null): OrderResponseInterface
    {
        $orderId = $this->guestQuoteManagement->placeOrder($cartId, $paymentMethod);
        $orderRealId = $this->checkoutSession->getLastRealOrderId();

        return $this->orderResponseFactory->create([
            OrderResponseInterface::ORDER_ID => (string)$orderId,
            OrderResponseInterface::ORDER_REAL_ID => (string)$orderRealId
        ]);
    }
}
