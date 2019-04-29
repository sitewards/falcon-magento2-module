<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Model;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class QueryCollectionServiceInterface
 * @package Deity\CatalogSearch\Model
 */
interface QueryCollectionServiceInterface
{
    /**
     * Executes all processors and applies query to collection
     *
     * @param Collection $collection
     * @param string $query
     *
     * @return void
     */
    public function apply(Collection $collection, string $query);
}
