<?php
declare(strict_types=1);

namespace Deity\MagentoApi\Model\Sales;

use Deity\MagentoApi\Api\Sales\OrderManagementInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\ResourceModel\Order\Payment\Collection as OrderPaymentCollection;
use Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory as OrderPaymentCollectionFactory;

/**
 * Class OrderManagement
 * @package Deity\MagentoApi\Model\Sales
 */
class OrderManagement implements OrderManagementInterface
{


    /** @var OrderPaymentCollectionFactory */
    private $orderPaymentCollectionFactory;

    /**
     * OrderManagement constructor.
     * @param OrderPaymentCollectionFactory $orderPaymentCollectionFactory
     */
    public function __construct(OrderPaymentCollectionFactory $orderPaymentCollectionFactory)
    {
        $this->orderPaymentCollectionFactory = $orderPaymentCollectionFactory;
    }


    /**
     * Get order id from hash generated when asking for paypal express checkout token
     *
     * @param string $paypalHash
     * @return int
     */
    public function getOrderIdFromHash(string $paypalHash): int
    {
        /** @var OrderPaymentCollection $collection */
        $collection = $this->orderPaymentCollectionFactory->create();

        $collection->addFieldToFilter(
            'additional_information',
            ['like' => "%\"paypalExpressHash\":\"{$paypalHash}\"%"]
        );
        /** @var OrderPaymentInterface $orderPayment */
        $orderPayment = $collection->getFirstItem();

        return (int)$orderPayment->getParentId();
    }
}
