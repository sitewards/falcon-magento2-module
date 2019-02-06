<?php
declare(strict_types=1);

namespace Deity\Quote\Model;

use Deity\QuoteApi\Api\CartManagementInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterface;
use Deity\QuoteApi\Api\Data\OrderResponseInterfaceFactory;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartManagementInterface as MagentoCartManagementInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class CartManagement
 *
 * @package Deity\Quote\Model
 */
class CartManagement implements CartManagementInterface
{

    /**
     * @var MagentoCartManagementInterface
     */
    private $quoteManagement;

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
     * @param MagentoCartManagementInterface $quoteManagement
     * @param Session $checkoutSession
     * @param OrderResponseInterfaceFactory $orderResponseFactory
     */
    public function __construct(
        MagentoCartManagementInterface $quoteManagement,
        Session $checkoutSession,
        OrderResponseInterfaceFactory $orderResponseFactory
    ) {
        $this->orderResponseFactory = $orderResponseFactory;
        $this->checkoutSession = $checkoutSession;
        $this->quoteManagement = $quoteManagement;
    }

    /**
     * Places an order for a specified cart.
     *
     * @param int $cartId The cart ID.
     * @param PaymentInterface|null $paymentMethod
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Deity\QuoteApi\Api\Data\OrderResponseInterface
     */
    public function placeOrder($cartId, PaymentInterface $paymentMethod = null): OrderResponseInterface
    {
        $orderId = $this->quoteManagement->placeOrder($cartId, $paymentMethod);
        $orderRealId = $this->checkoutSession->getLastRealOrderId();

        return $this->orderResponseFactory->create([
            OrderResponseInterface::ORDER_ID => (string)$orderId,
            OrderResponseInterface::ORDER_REAL_ID => (string)$orderRealId
        ]);
    }
}
