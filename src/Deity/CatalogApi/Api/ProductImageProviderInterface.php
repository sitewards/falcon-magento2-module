<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Magento\Catalog\Model\Product;

/**
 * Interface ProductImageProviderInterface
 * @package Deity\CatalogApi\Api
 */
interface ProductImageProviderInterface
{
    /**
     * @param Product $product
     * @param string $imageType
     * @return string
     */
    public function getProductImageTypeUrl(Product $product, string $imageType): string;
}
