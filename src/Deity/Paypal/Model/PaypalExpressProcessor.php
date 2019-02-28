<?php
declare(strict_types=1);

namespace Deity\Paypal\Model;

use Deity\PaypalApi\Api\Data\PaypalDataInterface;
use Deity\PaypalApi\Api\Data\PaypalDataInterfaceFactory;
use Magento\Checkout\Helper\Data;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Paypal\Model\Config;
use Magento\Paypal\Model\ConfigFactory;
use Magento\Paypal\Model\Express\Checkout;
use Magento\Paypal\Model\Express\Checkout\Factory as PaypalCheckoutFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class PaypalExpressProcessor
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @package Deity\Paypal\Model
 */
class PaypalExpressProcessor implements PaypalExpressProcessorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Url
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Quote Mask Factory
     * @var QuoteIdMaskFactory
     */
    private $quoteMaskFactory;

    /**
     * Cart Repository
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * Quote
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;

    /**
     * Internal cache of checkout models
     *
     * @var array
     */
    private $checkoutTypes = [];

    /**
     * @var Checkout\Factory
     */
    private $checkoutFactory;

    /**
     * @var PaypalDataInterfaceFactory
     */
    private $paypalDataFactory;

    /**
     * @var Data
     */
    private $checkoutHelper;

    /**
     * Payment constructor.
     * @param UrlInterface $urlBuilder
     * @param Data $checkoutHelper
     * @param QuoteIdMaskFactory $quoteMaskFactory
     * @param CartRepositoryInterface $cartRepository
     * @param Checkout\Factory $checkoutFactory
     * @param PaypalDataInterfaceFactory $paypalDataFactory
     * @param ConfigFactory $configFactory
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Data $checkoutHelper,
        QuoteIdMaskFactory $quoteMaskFactory,
        CartRepositoryInterface $cartRepository,
        PaypalCheckoutFactory $checkoutFactory,
        PaypalDataInterfaceFactory $paypalDataFactory,
        ConfigFactory $configFactory
    ) {
        $this->paypalDataFactory = $paypalDataFactory;
        $this->urlBuilder = $urlBuilder;
        $this->checkoutHelper = $checkoutHelper;
        $this->quoteMaskFactory = $quoteMaskFactory;
        $this->cartRepository = $cartRepository;
        $this->checkoutFactory = $checkoutFactory;
        $this->config = $configFactory->create(
            ['params' => [Config::METHOD_WPP_EXPRESS]]
        );
    }

    /**
     * Get Express checkout instance
     *
     * @return Checkout
     * @throws LocalizedException
     */
    private function getCheckout(): Checkout
    {
        if (!isset($this->checkoutTypes[Checkout::class])) {
            if (!$this->quote->hasItems() || $this->quote->getHasError()) {
                throw new LocalizedException(__('We can\'t initialize Express Checkout.'));
            }

            $parameters = [
                'params' => [
                    'quote' => $this->quote,
                    'config' => $this->config,
                ],
            ];
            $this->checkoutTypes[Checkout::class] = $this->checkoutFactory
                ->create(Checkout::class, $parameters);
        }
        return $this->checkoutTypes[Checkout::class];
    }
    /**
     * Create paypal token
     *
     * @param string $cartId
     * @return string
     * @throws LocalizedException
     */
    private function createToken(string $cartId): string
    {
        $this->quote = $this->cartRepository->getActive($cartId);
        $hasButton = false; // @todo needs to be parametrized. Parameter: button=[1 / 0]
        /** @var Data $checkoutHelper */
        $quoteCheckoutMethod = $this->quote->getCheckoutMethod();
        if ($this->quote->getIsMultiShipping()) {
            $this->quote->setIsMultiShipping(0);
            $this->quote->removeAllAddresses();
        }
        if ((!$quoteCheckoutMethod || $quoteCheckoutMethod !== Onepage::METHOD_REGISTER)
            && !$this->checkoutHelper->isAllowedGuestCheckout($this->quote, $this->quote->getStoreId())
        ) {
            throw new LocalizedException(__('To check out, please sign in with your email address.'));
        }
        // billing agreement
        $this->getCheckout()->setIsBillingAgreementRequested(false);
        // Bill Me Later
        $this->getCheckout()->setIsBml(false); // @todo needs to be parametrized. Parameter: bml=[1 / 0]
        // giropay
        $this->getCheckout()->prepareGiropayUrls(
            $this->urlBuilder->getUrl('checkout/onepage/success'),
            $this->urlBuilder->getUrl('paypal/express/cancel', ['cart_id' => $cartId]),
            $this->urlBuilder->getUrl('checkout/onepage/success')
        );
        return $this->getCheckout()->start(
            $this->urlBuilder->getUrl('checkoutExt/payment_paypal_express/return', ['cart_id' => $cartId]),
            $this->urlBuilder->getUrl('checkoutExt/payment_paypal_express/cancel', ['cart_id' => $cartId]),
            $hasButton
        );
    }

    /**
     * Create paypal token data
     *
     * @param string $cartId
     * @return PaypalDataInterface
     */
    public function createPaypalData(string $cartId): PaypalDataInterface
    {
        try {
            $token = $this->createToken($cartId);
            $url = $this->getCheckout()->getRedirectUrl();

            $paymentData = $this->paypalDataFactory->create(
                [
                    PaypalDataInterface::TOKEN => $token,
                    PaypalDataInterface::URL => $url,
                ]
            );
        } catch (LocalizedException $e) {
            $paymentData = $this->paypalDataFactory->create(
                [
                    PaypalDataInterface::ERROR => $e->getMessage()
                ]
            );
        }
        return $paymentData;
    }
}
