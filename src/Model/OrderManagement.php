<?php

namespace Hatimeria\Reagento\Model;

use Hatimeria\Reagento\Api\HatimeriaOrderManagementInterface;
use Hatimeria\Reagento\Model\Sales\Order\Extension as OrderExtension;
use Hatimeria\Reagento\Model\Sales\OrderItem\Extension as OrderItemExtension;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderManagement implements HatimeriaOrderManagementInterface
{

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var UserContextInterface */
    protected $userContext;

    /** @var SearchResultFactory */
    protected $searchResultFactory;

    /** @var OrderExtension */
    private $orderExtension;

    /** @var OrderItemExtension */
    protected $orderItemExtension;

    /**
     * OrderManagement constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param UserContextInterface $userContext
     * @param SearchResultFactory $searchResultFactory
     * @param OrderExtension $orderExtension
     * @param OrderItemExtension $orderItemExtension
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        UserContextInterface $userContext,
        SearchResultFactory $searchResultFactory,
        OrderExtension $orderExtension,
        OrderItemExtension $orderItemExtension
    ) {
        $this->orderRepository = $orderRepository;
        $this->userContext = $userContext;
        $this->searchResultFactory = $searchResultFactory;
        $this->orderItemExtension = $orderItemExtension;
        $this->orderExtension = $orderExtension;
    }

    /**
     * @param $orderId
     * @return OrderInterface
     * @throws AuthorizationException
     * @throws NoSuchEntityException
     */
    public function getItem($orderId)
    {
        $this->checkCustomerContext();
        $order = $this->orderRepository->get($orderId);
        $this->addOrderItemExtensionAttributes($order);
        if(!$order->getId() || $order->getCustomerId() !== $this->getCustomerId()) {
            throw new NoSuchEntityException(__('Unable to find order %orderId', ['orderId' => $orderId]));
        }

        return $order;
    }

    /**
     * Get list of customer order items
     *
     * @param SearchCriteria|null $searchCriteria
     * @return OrderSearchResultInterface
     * @throws AuthorizationException
     */
    public function getCustomerOrders(SearchCriteria $searchCriteria = null)
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
    protected function getCustomerId()
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
    protected function addFilterGroupToCollection(
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
        if ($fields) {
            $searchResult->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param OrderInterface $order
     */
    protected function addOrderExtensionAttributes(OrderInterface $order)
    {
        $this->orderExtension->addAttributes($order);
    }

    /**
     * @param OrderInterface $order
     */
    protected function addOrderItemExtensionAttributes(OrderInterface $order)
    {
        foreach($order->getItems() as $item) { /** @var OrderItemInterface $item */
            $this->orderItemExtension->addAttributes($item);
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