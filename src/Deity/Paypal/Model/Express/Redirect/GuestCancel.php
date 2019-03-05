<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Deity\Paypal\Model\Express\PaypalManagementInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterfaceFactory;
use Deity\PaypalApi\Api\Express\GuestCancelInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask as QuoteIdMaskResource;
use Psr\Log\LoggerInterface;

/**
 * Class GuestCancel
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
class GuestCancel implements GuestCancelInterface
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
     * CustomerReturn constructor.
     * @param PaypalManagementInterface $paypalManagement
     * @param CartRepositoryInterface $cartRepository
     * @param RedirectToFalconProviderInterface $redirectToFalconProvider
     * @param RedirectDataInterfaceFactory $redirectDataFactory
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param QuoteIdMaskResource $quoteIdMaskResource
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
        LoggerInterface $logger,
        Url $urlBuilder
    ) {
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
     * @return \Deity\PaypalApi\Api\Data\Express\RedirectDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function processCancel(string $cartId): RedirectDataInterface
    {
        $quote = $this->cartRepository->getActive($this->getQuoteIdFromMaskedId($cartId));
        $cartId = (string)$quote->getEntityId();
        try {
            $this->paypalManagement->unsetToken($cartId);
            $redirectUrlFailure = $this->redirectToFalconProvider->getFailureUrl($quote);
            $redirectUrlCancel = $this->redirectToFalconProvider->getCancelUrl($quote);
        } catch (LocalizedException $e) {
            $this->logger->critical('PayPal Cancel Action: ' . $e->getMessage());
            $redirectUrlCancel = $redirectUrlFailure;
        }

        return $this->redirectDataFactory->create([RedirectDataInterface::REDIRECT_FIELD => $redirectUrlCancel]);
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
