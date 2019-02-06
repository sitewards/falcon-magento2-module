<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Magento\Catalog\Model\Product;

/**
 * Interface ProductPriceProviderInterface
 *
 * @package Deity\CatalogApi\Api
 */
interface ProductPriceProviderInterface
{

    /**
     * Get price data
     *
     * @param Product $product
     * @return ProductPriceInterface
     */
    public function getPriceData(Product $product): ProductPriceInterface;
}
