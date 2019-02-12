<?php
declare(strict_types=1);

namespace Deity\Sales\Model;

use Deity\Sales\Model\ResourceModel\OrderIdMask as ResourceOrderIdMask;
use Deity\SalesApi\Api\Data\OrderIdMaskInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * OrderIdMask model
 */
class OrderIdMask extends AbstractModel implements OrderIdMaskInterface
{
    /**
     * Initialize resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceOrderIdMask::class);
    }

    /**
     * Get masked id
     *
     * @return string
     */
    public function getMaskedId(): string
    {
        return (string)$this->getData(ResourceOrderIdMask::MASKED_ID_FIELD_NAME);
    }

    /**
     * Set masked id
     *
     * @param string $maskedId
     * @return OrderIdMaskInterface
     */
    public function setMaskedId(string $maskedId): OrderIdMaskInterface
    {
        $this->setData(ResourceOrderIdMask::MASKED_ID_FIELD_NAME, $maskedId);
        return $this;
    }

    /**
     * Get order id
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return (int)$this->getData(ResourceOrderIdMask::ORDER_ID_FIELD_NAME);
    }

    /**
     * Set order id
     *
     * @param int $orderId
     * @return OrderIdMaskInterface
     */
    public function setOrderId(int $orderId): OrderIdMaskInterface
    {
        $this->setData(ResourceOrderIdMask::ORDER_ID_FIELD_NAME, $orderId);
        return $this;
    }
}
