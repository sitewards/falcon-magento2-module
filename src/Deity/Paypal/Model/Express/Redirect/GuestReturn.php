<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Deity\Paypal\Model\Express\PaypalManagementInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterfaceFactory;
use Deity\PaypalApi\Api\Express\GuestReturnInterface;
use Deity\SalesApi\Api\OrderIdMaskRepositoryInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url;
use Magento\Paypal\Model\Express\Checkout;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask as QuoteIdMaskResource;
use Psr\Log\LoggerInterface;

/**
 * Class GuestReturn
 *
 * @package Deity\Paypal\Model\Redirect
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GuestReturn implements GuestReturnInterface
{

    /**
     * @var PaypalManagementInterface
     */
    private $paypalManagement;

    /**
     * @var RedirectToFalconProviderInterface
     */
    private $redirectToFalconProvider;

    /**
     * @var RedirectDataInterfaceFactory
     */
    private $redirectDataFactory;

    /**
     * Cart Repository
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var LoggerInterface;
     */
    private $logger;

    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var QuoteIdMaskResource
     */
    private $quoteIdMaskResource;

    /**
     * @var OrderIdMaskRepositoryInterface
     */
    private $orderIdMaskRepository;

    /**
     * CustomerReturn constructor.
     * @param PaypalManagementInterface $paypalManagement
     * @param CartRepositoryInterface $cartRepository
     * @param RedirectToFalconProviderInterface $redirectToFalconProvider
     * @param RedirectDataInterfaceFactory $redirectDataFactory
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param QuoteIdMaskResource $quoteIdMaskResource
     * @param OrderIdMaskRepositoryInterface $orderIdMaskRepository
     * @param LoggerInterface $logger
     * @param Url $urlBuilder
     */
    public function __construct(
        PaypalManagementInterface $paypalManagement,
        CartRepositoryInterface $cartRepository,
        RedirectToFalconProviderInterface $redirectToFalconProvider,
        RedirectDataInterfaceFactory $redirectDataFactory,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        QuoteIdMaskResource $quoteIdMaskResource,
        OrderIdMaskRepositoryInterface $orderIdMaskRepository,
        LoggerInterface $logger,
        Url $urlBuilder
    ) {
        $this->orderIdMaskRepository = $orderIdMaskRepository;
        $this->quoteIdMaskResource = $quoteIdMaskResource;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->logger = $logger;
        $this->urlBuilder = $urlBuilder;
        $this->cartRepository = $cartRepository;
        $this->paypalManagement = $paypalManagement;
        $this->redirectToFalconProvider = $redirectToFalconProvider;
        $this->redirectDataFactory = $redirectDataFactory;
    }

    /**
     * Process return from paypal gateway
     *
     * @param string $cartId
     * @param string $token
     * @param string $PayerID
     * @return \Deity\PaypalApi\Api\Data\Express\RedirectDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function processReturn(string $cartId, string $token, string $PayerID): RedirectDataInterface
    {
        $quote = $this->cartRepository->getActive($this->getQuoteIdFromMaskedId($cartId));
        $orderId = '';
        $orderIncrementId = '';
        try {
            $cartId = (string)$quote->getEntityId();
            /** @var Checkout $checkout */
            $checkout = $this->paypalManagement->getExpressCheckout($cartId);
            $this->paypalManagement->validateToken($cartId, $token);
            $checkout->returnFromPaypal($token);

            if (!$checkout->canSkipOrderReviewStep()) {
                throw new LocalizedException(__('Review page is not supported!'));
            }
            $checkout->place($token);

            // redirect if PayPal specified some URL (for example, to Giropay bank)
            $url = $checkout->getRedirectUrl();
            if ($url) {
                throw new LocalizedException(__('Giropay pay is not supported!'));
            }

            $redirectUrl = $this->redirectToFalconProvider->getSuccessUrl($quote);
            $message = __('Your Order got a number: #%1', $checkout->getOrder()->getIncrementId());
            $orderIdMasked = $this->orderIdMaskRepository->get((int)$checkout->getOrder()->getId());
            $orderId = $orderIdMasked->getMaskedId();
            $orderIncrementId = $checkout->getOrder()->getIncrementId();
        } catch (LocalizedException $e) {
            $this->logger->critical('PayPal Return Action: ' . $e->getMessage());
            $redirectUrl = $this->redirectToFalconProvider->getFailureUrl($quote);
            $message = __('Reason: %1', $e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical('PayPal Return Action: ' . $e->getMessage());
            $message = __('Reason: %1', $e->getMessage());
            $redirectUrl = $this->redirectToFalconProvider->getFailureUrl($quote);
        }

        $redirectParams = [
            RedirectDataInterface::REDIRECT_FIELD => $redirectUrl,
            RedirectDataInterface::UENC_FIELD => base64_encode((string)$message),
            RedirectDataInterface::ORDER_ID_FIELD => $orderId,
            RedirectDataInterface::REAL_ORDER_ID_FIELD => $orderIncrementId,

        ];

        return $this->redirectDataFactory->create($redirectParams);
    }

    /**
     * Get quote id
     *
     * @param string $maskedId
     * @return string
     * @throws NoSuchEntityException
     */
    private function getQuoteIdFromMaskedId(string $maskedId)
    {
        /** @var QuoteIdMask $quoteMask */
        $quoteMask = $this->quoteIdMaskFactory->create();
        $this->quoteIdMaskResource->load($quoteMask, $maskedId, 'masked_id');

        if ($quoteMask->getQuoteId() === null) {
            throw new NoSuchEntityException(__('Given cart does not exist or is not active.'));
        }

        return (string)$quoteMask->getQuoteId();
    }
}
