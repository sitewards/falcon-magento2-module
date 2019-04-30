<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductStockExtensionInterface;
use Deity\CatalogApi\Api\Data\ProductStockInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

/**
 * Class ProductStock
 *
 * @package Deity\Catalog\Model\Data
 */
class ProductStock implements ProductStockInterface
{

    /**
     * @var int
     */
    private $qty;

    /**
     * @var bool
     */
    private $isInStock;

    /**
     * @var int
     */
    private $minQty;

    /**
     * @var ProductStockExtensionInterface
     */
    private $extensionAttributes;

    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * ProductStock constructor.
     * @param int $qty
     * @param bool $is_in_stock
     * @param int $min_qty
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     */
    public function __construct(
        int $qty,
        bool $is_in_stock,
        int $min_qty,
        ExtensionAttributesFactory $extensionAttributesFactory
    ) {
        $this->qty = $qty;
        $this->isInStock = $is_in_stock;
        $this->minQty = $min_qty;
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * Get product qty
     *
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * Get is in stock flag
     *
     * @return bool
     */
    public function getIsInStock(): bool
    {
        return $this->isInStock;
    }

    /**
     * Get min qty
     *
     * @return int
     */
    public function getMinQty(): int
    {
        return $this->minQty;
    }

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductStockExtensionInterface
     */
    public function getExtensionAttributes()
    {
        if ($this->extensionAttributes === null) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(ProductStockInterface::class);
        }
        return $this->extensionAttributes;
    }

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductStockExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductStockInterface
     */
    public function setExtensionAttributes(ProductStockExtensionInterface $extensionAttributes): ProductStockInterface
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }
}
