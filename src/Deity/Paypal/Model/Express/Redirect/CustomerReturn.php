<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Deity\Paypal\Model\Express\PaypalManagementInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterfaceFactory;
use Deity\PaypalApi\Api\Express\CustomerReturnInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Url;
use Magento\Paypal\Model\Express\Checkout;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerReturn
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
class CustomerReturn implements CustomerReturnInterface
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
     * CustomerReturn constructor.
     * @param PaypalManagementInterface $paypalManagement
     * @param CartRepositoryInterface $cartRepository
     * @param RedirectToFalconProviderInterface $redirectToFalconProvider
     * @param RedirectDataInterfaceFactory $redirectDataFactory
     * @param LoggerInterface $logger
     * @param Url $urlBuilder
     */
    public function __construct(
        PaypalManagementInterface $paypalManagement,
        CartRepositoryInterface $cartRepository,
        RedirectToFalconProviderInterface $redirectToFalconProvider,
        RedirectDataInterfaceFactory $redirectDataFactory,
        LoggerInterface $logger,
        Url $urlBuilder
    ) {
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
        $quote = $this->cartRepository->getActive($cartId);
        $orderId = '';
        $orderIncrementId = '';
        try {
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
            $orderId = $checkout->getOrder()->getId();
            $orderIncrementId = $checkout->getOrder()->getIncrementId();
        } catch (LocalizedException $e) {
            $this->logger->critical('PayPal customer return action: ' . $e->getMessage());
            $redirectUrl = $this->redirectToFalconProvider->getFailureUrl($quote);
            $message = __('Reason: %1', $e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical('PayPal customer return action: ' . $e->getMessage());
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
}
