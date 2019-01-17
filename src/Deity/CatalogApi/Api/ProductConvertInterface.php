<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Deity\CatalogApi\Api\Data\ProductInterface as DeityProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface ConvertInterface
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductConvertInterface
{
    /**
     * Convert product to array representation
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function convert(\Magento\Catalog\Model\Product $product): DeityProductInterface;

    /**
     * @return ProductInterface
     */
    public function getCurrentProduct(): ProductInterface;
}
