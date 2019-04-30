<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductStockInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductStockInterface extends ExtensibleDataInterface
{
    const QTY_FIELD_KEY = 'qty';
    const IS_IN_STOCK_FIELD_KEY = 'is_in_stock';
    const MIN_QTY_FIELD_KEY = 'min_qty';

    /**
     * Get product qty
     *
     * @return int
     */
    public function getQty(): int;

    /**
     * Get is in stock flag
     *
     * @return bool
     */
    public function getIsInStock(): bool;

    /**
     * Get min qty
     *
     * @return int
     */
    public function getMinQty(): int;

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductStockExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductStockExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductStockInterface
     */
    public function setExtensionAttributes(
        ProductStockExtensionInterface $extensionAttributes
    ): ProductStockInterface;
}
