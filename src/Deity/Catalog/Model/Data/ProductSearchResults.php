<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class ProductSearchResults
 *
 * @package Deity\Catalog\Model\Data
 */
class ProductSearchResults extends AbstractSimpleObject implements ProductSearchResultsInterface
{
    /**
     * @inheritdoc
     */
    public function getFilters(): array
    {
        return $this->_get(self::KEY_FILTERS) === null ? [] : $this->_get(self::KEY_FILTERS);
    }

    /**
     * @inheritdoc
     */
    public function setFilters(array $items): ProductSearchResultsInterface
    {
        $this->setData(self::KEY_FILTERS, $items);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getItems(): array
    {
        return $this->_get(self::KEY_ITEMS);
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items): ProductSearchResultsInterface
    {
        $this->setData(self::KEY_ITEMS, $items);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTotalCount(): int
    {
        return (int)$this->_get(self::KEY_TOTAL_COUNT);
    }

    /**
     * @inheritdoc
     */
    public function setTotalCount(int $totalCount): ProductSearchResultsInterface
    {
        $this->setData(self::KEY_TOTAL_COUNT, $totalCount);
        return $this;
    }
}
