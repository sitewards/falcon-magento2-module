<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model\QueryCollection;

use Deity\CatalogSearchApi\Model\QueryCollectionServiceInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class QueryCollectionService
 *
 * @package Deity\CatalogSearch\Model
 */
class Service implements QueryCollectionServiceInterface
{

    /**
     * Apply search query to Collection
     *
     * @param Collection $collection
     * @param string $query
     *
     * @return void
     */
    public function apply(Collection $collection, string $query)
    {
        $collection->addSearchFilter($query);
    }
}
