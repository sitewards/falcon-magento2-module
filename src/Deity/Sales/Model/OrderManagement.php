<?php
declare(strict_types=1);

namespace Deity\Sales\Model;

use Deity\Sales\Model\Order\Item\ReadHandler as ItemReadHandler;
use Deity\Sales\Model\Order\Payment\ReadHandler as PaymentReadHandler;
use Deity\Sales\Model\Order\ReadHandler;
use Deity\SalesApi\Api\OrderManagementInterface;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderManagement
 *
 * @package Deity\Sales\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderManagement implements OrderManagementInterface
{

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var UserContextInterface */
    private $userContext;

    /** @var SearchResultFactory */
    private $searchResultFactory;

    /** @var OrderExtension */
    private $orderReadHandler;

    /** @var ItemReadHandler */
    private $orderItemReadHandler;

    /** @var PaymentReadHandler */
    private $orderPaymentReadHandler;

    /** @var Manager */
    private $eventManager;

    /**
     * OrderManagement constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param UserContextInterface $userContext
     * @param SearchResultFactory $searchResultFactory
     * @param ReadHandler $orderExtension
     * @param ItemReadHandler $orderItemExtension
     * @param PaymentReadHandler $orderPaymentExtension
     * @param Manager $eventManager
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        UserContextInterface $userContext,
        SearchResultFactory $searchResultFactory,
        ReadHandler $orderExtension,
        ItemReadHandler $orderItemExtension,
        PaymentReadHandler $orderPaymentExtension,
        Manager $eventManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->userContext = $userContext;
        $this->searchResultFactory = $searchResultFactory;
        $this->orderItemReadHandler = $orderItemExtension;
        $this->orderReadHandler = $orderExtension;
        $this->orderPaymentReadHandler = $orderPaymentExtension;
        $this->eventManager = $eventManager;
    }

    /**
     * Get item
     *
     * @param int $orderId
     * @return OrderInterface
     * @throws AuthorizationException
     * @throws NoSuchEntityException
     */
    public function getItem(int $orderId): OrderInterface
    {
        $this->checkCustomerContext();
        $order = $this->orderRepository->get($orderId);
        if (!$order->getId() || $order->getCustomerId() !== $this->getCustomerId()) {
            throw new NoSuchEntityException(__('Unable to find order %orderId', ['orderId' => $orderId]));
        }

        $this->addOrderExtensionAttributes($order);
        $this->addOrderPaymentExtensionAttributes($order->getPayment());
        $this->addOrderItemExtensionAttributes($order);

        return $order;
    }

    /**
     * Get list of customer order items
     *
     * @param SearchCriteria|null $searchCriteria
     * @return OrderSearchResultInterface
     * @throws AuthorizationException
     */
    public function getCustomerOrders(SearchCriteria $searchCriteria = null): OrderSearchResultInterface
    {
        $this->checkCustomerContext();
        /** @var \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->addFieldToFilter('customer_id', ['eq' => $this->getCustomerId()]);
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $this->addFilterGroupToCollection($filterGroup, $searchResult);
        }

        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders === null) {
            $sortOrders = [];
        }
        /** @var \Magento\Framework\Api\SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $searchResult->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $this->eventManager->dispatch(
            'order_management_customer_orders_before',
            ['search_criteria', $searchCriteria]
        );

        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setCurPage($searchCriteria->getCurrentPage());
        $searchResult->setPageSize($searchCriteria->getPageSize());
        foreach ($searchResult->getItems() as $order) {
            $this->addOrderExtensionAttributes($order);
        }
        return $searchResult;
    }

    /**
     * Get user id from context
     *
     * @return int|null
     */
    private function getCustomerId()
    {
        return $this->userContext->getUserId();
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param OrderSearchResultInterface $searchResult
     * @return void
     */
    private function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        OrderSearchResultInterface $searchResult
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $conditions[] = [$condition => $filter->getValue()];
            $fields[] = $filter->getField();
        }

        $transport = new DataObject(['fields' => $fields, 'conditions' => $conditions]);
        $this->eventManager->dispatch(
            'order_management_prepare_filter_group',
            ['filter_group' => $filterGroup, 'data' => $transport]
        );

        $fields = $transport->getFields();
        $conditions = $transport->getConditions();

        if ($fields) {
            $searchResult->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * Add extension attributes to order entity
     *
     * @param OrderInterface $order
     */
    private function addOrderExtensionAttributes(OrderInterface $order)
    {
        $this->orderReadHandler->execute($order);
    }

    /**
     * Add extension attributes to order payment
     *
     * @param OrderPaymentInterface $payment
     */
    private function addOrderPaymentExtensionAttributes(OrderPaymentInterface $payment)
    {
        $this->orderPaymentReadHandler->execute($payment);
    }

    /**
     * Add extension attributes to order items
     *
     * @param OrderInterface $order
     */
    private function addOrderItemExtensionAttributes(OrderInterface $order)
    {
        foreach ($order->getItems() as $item) {
            /** @var OrderItemInterface $item */
            $this->orderItemReadHandler->execute($item);
        }
    }

    /**
     * Check if current user context is for logged in customer
     *
     * @return bool
     * @throws AuthorizationException
     */
    private function checkCustomerContext()
    {
        if ($this->userContext->getUserType() !== UserContextInterface::USER_TYPE_CUSTOMER) {
            throw new AuthorizationException(__('This method is available only for customer tokens'));
        }

        return true;
    }
}
