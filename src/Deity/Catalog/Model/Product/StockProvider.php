<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Api\Data\ProductStockInterface;
use Deity\CatalogApi\Api\Data\ProductStockInterfaceFactory;
use Deity\CatalogApi\Model\ProductStockProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

/**
 * Class StockProvider
 *
 * @package Deity\Catalog\Model\Product
 */
class StockProvider implements ProductStockProviderInterface
{

    /**
     * @var ProductStockInterfaceFactory
     */
    private $productStockFactory;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * StockProvider constructor.
     * @param ProductStockInterfaceFactory $productStockFactory
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        ProductStockInterfaceFactory $productStockFactory,
        StockRegistryInterface $stockRegistry
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->productStockFactory = $productStockFactory;
    }

    /**
     * Get stock data for given product
     *
     * @param ProductInterface $product
     * @return ProductStockInterface
     */
    public function getStockData(ProductInterface $product): ProductStockInterface
    {
        /** @var StockItemInterface $stockItem */
        $stockItem = $this->stockRegistry->getStockItem($product->getId());

        return $this->productStockFactory->create(
            [
                ProductStockInterface::QTY_FIELD_KEY => (int)$stockItem->getQty(),
                ProductStockInterface::MIN_QTY_FIELD_KEY => (int)$stockItem->getMinQty(),
                ProductStockInterface::IS_IN_STOCK_FIELD_KEY => (bool)$stockItem->getIsInStock(),
            ]
        );
    }
}
