<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Magento\Catalog\Model\Layer;

/**
 * Interface ProductFilterProvider
 * @package Deity\CatalogApi\Api
 */
interface ProductFilterProviderInterface
{
    /**
     * @param Layer $layer
     * @return \Deity\CatalogApi\Api\Data\FilterInterface[]
     */
    public function getFilterList(Layer $layer): array;
}
