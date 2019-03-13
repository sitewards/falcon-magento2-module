<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteIdMask as QuoteIdMaskObject;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask;

/**
 * Class RedirectToFalcon
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
class RedirectToFalconProvider implements RedirectToFalconProviderInterface
{

    const SUCCESS_URL_DATA_KEY = 'redirect_success';

    const CANCEL_URL_DATA_KEY = 'redirect_cancel';

    const FAILURE_URL_DATA_KEY = 'redirect_failure';

    const PAYPAL_RETURN_SUCCESS = 'paypal_return_success';

    const PAYPAL_RETURN_CANCEL = 'paypal_return_cancel';

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteMaskIdFactory;

    /**
     * @var QuoteIdMask
     */
    private $quoteIdMaskResource;

    /**
     * RedirectToFalconProvider constructor.
     *
     * @param QuoteIdMaskFactory $quoteMaskIdFactory
     * @param QuoteIdMask $quoteIdMaskResource
     */
    public function __construct(QuoteIdMaskFactory $quoteMaskIdFactory, QuoteIdMask $quoteIdMaskResource)
    {
        $this->quoteMaskIdFactory = $quoteMaskIdFactory;
        $this->quoteIdMaskResource = $quoteIdMaskResource;
    }

    /**
     * Get success url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getSuccessUrl(CartInterface $quote): string
    {
        return (string)$quote->getPayment()->getAdditionalInformation(self::SUCCESS_URL_DATA_KEY);
    }

    /**
     * Get cancel url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getCancelUrl(CartInterface $quote): string
    {
        return (string)$quote->getPayment()->getAdditionalInformation(self::CANCEL_URL_DATA_KEY);
    }

    /**
     * Get failure url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getFailureUrl(CartInterface $quote): string
    {
        return (string)$quote->getPayment()->getAdditionalInformation(self::FAILURE_URL_DATA_KEY);
    }

    /**
     * Get paypal return success url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getPaypalReturnSuccessUrl(CartInterface $quote): string
    {
        $redirectUrl = (string)$quote->getPayment()->getAdditionalInformation(self::PAYPAL_RETURN_SUCCESS);
        return $this->appendCartDataIfNeeded($quote, $redirectUrl);
    }

    /**
     * Get paypal return cancel url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getPaypalReturnCancelUrl(CartInterface $quote): string
    {
        $redirectUrl = (string)$quote->getPayment()->getAdditionalInformation(self::PAYPAL_RETURN_CANCEL);
        return  $this->appendCartDataIfNeeded($quote, $redirectUrl);
    }

    /**
     * Append cart masked Id for guest cards
     *
     * @param CartInterface $quote
     * @param string $redirectUrl
     * @return string
     */
    private function appendCartDataIfNeeded(CartInterface $quote, string $redirectUrl): string
    {
        if ($quote->getCustomerIsGuest()) {
            /** @var QuoteIdMaskObject $quoteIdMask */
            $quoteIdMask = $this->quoteMaskIdFactory->create();
            $this->quoteIdMaskResource->load($quoteIdMask, $quote->getId(), 'quote_id');

            $urlParams = http_build_query(['cartId' => $quoteIdMask->getMaskedId()]);
            $sep = (strpos($redirectUrl, '?') === false) ? '?' : '&';
            $redirectUrl = $redirectUrl . $sep . $urlParams;
        }

        return $redirectUrl;
    }
}
