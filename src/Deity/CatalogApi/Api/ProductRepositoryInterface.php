<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;

/**
 * Interface ProductDetailInterface
 *
 * @package Deity\CatalogApi\Api
 */
interface ProductRepositoryInterface
{
    /**
     * Get product info
     *
     * @param string $sku
     * @return \Deity\CatalogApi\Api\Data\ProductDetailInterface
     */
    public function get(string $sku): ProductDetailInterface;
}
