<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface ProductSearchResultsInterface
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductSearchResultsInterface
{

    const KEY_FILTERS = 'filters';

    const KEY_ITEMS = 'items';

    const KEY_TOTAL_COUNT = 'total_count';

    /**
     * Get filters
     *
     * @return \Deity\CatalogApi\Api\Data\FilterInterface[]
     */
    public function getFilters(): array;

    /**
     * Set filters
     *
     * @param \Deity\CatalogApi\Api\Data\FilterInterface[] $items
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function setFilters(array $items): ProductSearchResultsInterface;

    /**
     * Get items list.
     *
     * @return \Deity\CatalogApi\Api\Data\ProductInterface[]
     */
    public function getItems(): array;

    /**
     * Set items list.
     *
     * @param \Deity\CatalogApi\Api\Data\ProductInterface[] $items
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function setItems(array $items): ProductSearchResultsInterface;

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function setTotalCount(int $totalCount): ProductSearchResultsInterface;
}
