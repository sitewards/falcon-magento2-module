<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Deity\Paypal\Model\Express\PaypalManagementInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterfaceFactory;
use Deity\PaypalApi\Api\Express\CustomerCancelInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Url;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerCancel
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
class CustomerCancel implements CustomerCancelInterface
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
     * @return \Deity\PaypalApi\Api\Data\Express\RedirectDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function processCancel(string $cartId): RedirectDataInterface
    {
        $quote = $this->cartRepository->getActive($cartId);

        $redirectUrlFailure = '';
        try {
            $this->paypalManagement->unsetToken($cartId);
            $redirectUrlFailure = $this->redirectToFalconProvider->getFailureUrl($quote);
            $redirectUrlCancel = $this->redirectToFalconProvider->getCancelUrl($quote);
            $message = 'Order cancel done.';
        } catch (LocalizedException $e) {
            $message = 'PayPal Cancel Action: ' . $e->getMessage();
            $this->logger->critical($message);
            $redirectUrlCancel = $redirectUrlFailure;
        }

        $redirectParams = [
            RedirectDataInterface::REDIRECT_FIELD => $redirectUrlCancel,
            RedirectDataInterface::UENC_FIELD => base64_encode((string)$message),
            RedirectDataInterface::ORDER_ID_FIELD => '',
            RedirectDataInterface::REAL_ORDER_ID_FIELD => '',

        ];

        return $this->redirectDataFactory->create($redirectParams);
    }
}
