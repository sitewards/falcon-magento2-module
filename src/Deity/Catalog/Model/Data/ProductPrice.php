<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class ProductPrice
 * @package Deity\Catalog\Model\Data
 */
class ProductPrice implements ProductPriceInterface
{

    /**
     * @var float
     */
    private $regularPrice;

    /**
     * @var float
     */
    private $specialPrice;

    /**
     * @var float
     */
    private $minTierPrice;

    /**
     * ProductPrice constructor.
     * @param float $regularPrice
     * @param float $specialPrice
     * @param float $minTierPrice
     */
    public function __construct(float $regularPrice, float $specialPrice = null, float $minTierPrice = null)
    {
        $this->regularPrice = $regularPrice;
        $this->specialPrice = $specialPrice;
        $this->minTierPrice = $minTierPrice;
    }

    /**
     * @return float
     */
    public function getRegularPrice(): float
    {
        return $this->regularPrice;
    }

    /**
     * @return float
     */
    public function getSpecialPrice(): ?float
    {
        return $this->specialPrice;
    }

    /**
     * @return float
     */
    public function getMinTierPrice(): ?float
    {
        return $this->minTierPrice;
    }
}
