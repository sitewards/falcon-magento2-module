<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;
use Deity\PaypalApi\Api\Express\GuestTokenProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask as QuoteIdMaskResource;

/**
 * Class GuestTokenProvider
 *
 * @package Deity\Paypal\Model\Express
 */
class GuestTokenProvider implements GuestTokenProviderInterface
{
    /**
     * @var PaypalManagementInterface
     */
    private $paypalManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var QuoteIdMaskResource
     */
    private $quoteIdMaskResource;

    /**
     * GuestPaypalExpress constructor.
     * @param PaypalManagementInterface $paypalManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param QuoteIdMaskResource $quoteIdMaskResource
     */
    public function __construct(
        PaypalManagementInterface $paypalManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        QuoteIdMaskResource $quoteIdMaskResource
    ) {
        $this->paypalManagement = $paypalManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->quoteIdMaskResource = $quoteIdMaskResource;
    }

    /**
     * Get Token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\PaypalDataInterface
     * @throws NoSuchEntityException
     */
    public function getToken(string $cartId): PaypalDataInterface
    {
        /** @var QuoteIdMask $quoteMask */
        $quoteMask = $this->quoteIdMaskFactory->create();
        $this->quoteIdMaskResource->load($quoteMask, $cartId, 'masked_id');

        if ($quoteMask->getQuoteId() === null) {
            throw new NoSuchEntityException(__('Given cart does not exist or is not active.'));
        }

        return $this->paypalManagement->createPaypalData($quoteMask->getQuoteId());
    }
}
