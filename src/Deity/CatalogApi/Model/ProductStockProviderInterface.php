<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model;

use Deity\CatalogApi\Api\Data\ProductStockInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface ProductStockProviderInterface
 *
 * @package Deity\CatalogApi\Model
 */
interface ProductStockProviderInterface
{
    /**
     * Get stock data for given product
     *
     * @param ProductInterface $product
     * @return \Deity\CatalogApi\Api\Data\ProductStockInterface
     */
    public function getStockData(ProductInterface $product): ProductStockInterface;
}
