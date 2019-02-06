<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\Catalog\Model\Data\ProductPrice;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterfaceFactory;
use Deity\CatalogApi\Api\ProductPriceProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPriceInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;

/**
 * Class PriceProvider
 *
 * @package Deity\Catalog\Model\Product
 */
class PriceProvider implements ProductPriceProviderInterface
{

    /**
     * @var MinimalPriceCalculatorInterface
     */
    private $minimalPriceCalculator;

    /**
     * @var ProductPriceInterfaceFactory
     */
    private $productPriceFactory;

    /**
     * PriceProvider constructor.
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     * @param ProductPriceInterfaceFactory $productPriceFactory
     */
    public function __construct(
        MinimalPriceCalculatorInterface $minimalPriceCalculator,
        ProductPriceInterfaceFactory $productPriceFactory
    ) {
        $this->minimalPriceCalculator = $minimalPriceCalculator;
        $this->productPriceFactory = $productPriceFactory;
    }

    /**
     * @inheritdoc
     */
    public function getPriceData(Product $product): ProductPriceInterface
    {
        $priceInfo = $product->getPriceInfo();
        $regularPrice = $priceInfo->getPrice('regular_price');
        $regularPriceValue = $regularPrice->getValue();
        /** @var FinalPriceInterface $priceObject */
        $priceObject = $priceInfo->getPrice('final_price');
        $minPrice = $priceObject->getMinimalPrice();
        $minimalPrice = $this->minimalPriceCalculator->getValue($product);

        $specialPrice = $minPrice->getValue();
        if ($specialPrice >= $regularPriceValue) {
            $specialPrice = null;
        }

        /** @var ProductPrice $priceObject */
        $priceObject = $this->productPriceFactory->create(
            [
                'regularPrice' => $regularPriceValue,
                'specialPrice' => $specialPrice,
                'minTierPrice' => $minimalPrice
            ]
        );

        return $priceObject;
    }
}
