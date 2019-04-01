<?php
declare(strict_types=1);

namespace Deity\Quote\Model;

use Deity\QuoteApi\Api\CreateCustomerCartInterface;
use Deity\QuoteApi\Model\CartMergeManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartInterfaceFactory;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class GetCartForCustomer
 *
 * @package Deity\Quote\Model
 */
class CreateCustomerCart implements CreateCustomerCartInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var CartInterfaceFactory
     */
    private $quoteFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var AddressInterfaceFactory
     */
    private $quoteAddressFactory;

    /**
     * @var CartMergeManagementInterface
     */
    private $cartMergeManagement;

    /**
     * CreateCustomerCart constructor.
     * @param StoreManagerInterface $storeManager
     * @param CartRepositoryInterface $quoteRepository
     * @param CartInterfaceFactory $quoteFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AddressInterfaceFactory $addressInterfaceFactory
     * @param CartMergeManagementInterface $cartMergeManagement
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CartRepositoryInterface $quoteRepository,
        CartInterfaceFactory $quoteFactory,
        CustomerRepositoryInterface $customerRepository,
        AddressInterfaceFactory $addressInterfaceFactory,
        CartMergeManagementInterface $cartMergeManagement
    ) {
        $this->cartMergeManagement = $cartMergeManagement;
        $this->quoteAddressFactory = $addressInterfaceFactory;
        $this->storeManager = $storeManager;
        $this->quoteRepository = $quoteRepository;
        $this->quoteFactory = $quoteFactory;
        $this->customerRepository = $customerRepository;
    }
    
    /**
     * Get cart for customer
     *
     * @param int $customerId
     * @param string|null $maskedQuoteId
     * @return int
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute($customerId, string $maskedQuoteId = null): int
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        $quote = $this->createCustomerCart($customerId, $storeId);

        if ($maskedQuoteId !== null) {
            $this->cartMergeManagement->mergeGuestAndCustomerQuotes($maskedQuoteId, $quote);
        } else {
            $this->quoteRepository->save($quote);
        }

        return (int)$quote->getId();
    }

    /**
     * Creates a cart for the currently logged-in customer or provides existing one's
     *
     * @param int $customerId
     * @param int $storeId
     * @return CartInterface object.
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function createCustomerCart($customerId, $storeId): CartInterface
    {
        try {
            $quote = $this->quoteRepository->getActiveForCustomer($customerId);
        } catch (NoSuchEntityException $e) {
            $customer = $this->customerRepository->getById($customerId);
            /** @var Quote $quote */
            $quote = $this->quoteFactory->create();
            $quote->setStoreId($storeId);
            $quote->setCustomer($customer);
            $quote->setCustomerIsGuest(0);
            $quote->setBillingAddress($this->quoteAddressFactory->create());
            $quote->setShippingAddress($this->quoteAddressFactory->create());
        }
        return $quote;
    }
}
