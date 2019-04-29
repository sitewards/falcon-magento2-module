<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Api;

use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterface;

/**
 * Search API for all requests
 *
 * @api
 */
interface SearchInterface
{
    /**
     * Return products
     *
     * @param string $query
     * @param \Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function search(string $query, SearchCriteriaInterface $searchCriteria = null) :
        ProductSearchResultsInterface;
}
