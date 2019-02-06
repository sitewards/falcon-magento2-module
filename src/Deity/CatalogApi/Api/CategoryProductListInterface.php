<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;

/**
 * Interface CategoryProductListInterface
 *
 * @package Deity\CatalogApi\Api
 */
interface CategoryProductListInterface
{

    /**
     * Get list
     *
     * @param int $categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): ProductSearchResultsInterface;
}
