<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface ProductPriceInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductPriceInterface
{
    const REGULAR_PRICE = 'regular_price';

    const SPECIAL_PRICE = 'special_price';

    const MIN_TIER_PRICE = 'min_tier_price';

    /**
     * Get regular price
     *
     * @return float
     */
    public function getRegularPrice(): float;

    /**
     * Get special price
     *
     * @return float
     */
    public function getSpecialPrice(): ?float;

    /**
     * Get min tier price
     *
     * @return float
     */
    public function getMinTierPrice(): ?float;
}
