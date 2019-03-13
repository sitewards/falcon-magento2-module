<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Magento\Catalog\Model\Layer;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ProductFilterProvider
 *
 * @package Deity\CatalogApi\Api
 */
interface ProductFilterProviderInterface
{
    /**
     * Get filter list
     *
     * @param Layer $layer
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return \Deity\CatalogApi\Api\Data\FilterInterface[]
     */
    public function getFilterList(Layer $layer, ?SearchCriteriaInterface $searchCriteria): array;
}
