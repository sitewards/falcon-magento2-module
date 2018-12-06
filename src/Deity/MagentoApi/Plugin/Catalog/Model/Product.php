<?php

namespace Deity\MagentoApi\Plugin\Catalog\Model;

use Deity\MagentoApi\Helper\Product as DeityProductHelper;
use Magento\Catalog\Model\Product as MagentoProduct;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * @package Deity\MagentoApi\Model\Plugin
 */
class Product
{
    /**
     * @var DeityProductHelper
     */
    private $productHelper;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param DeityProductHelper $productHelper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        DeityProductHelper $productHelper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->productHelper = $productHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Add resized image information to the product's extension attributes.
     *
     * @param MagentoProduct $product
     * @return MagentoProduct
     * @throws LocalizedException
     */
    public function afterLoad(MagentoProduct $product)
    {
        $this->productHelper->ensureOptionsForConfigurableProduct($product);

        $this->productHelper->addProductImageAttribute($product);
        $this->productHelper->addProductImageAttribute($product, 'product_list_image', 'thumbnail_url');
        $this->productHelper->addMediaGallerySizes($product);

        if($product->getTypeId() !== 'configurable') {
            /** configurable product price is set to 0
             * and ensurePriceForConfigurableProduct may already set price with tax depending on settings
             * which makes display price calculation impossible
             **/
            $this->productHelper->calculateCatalogDisplayPrice($product);
        } else {
            $this->productHelper->ensurePriceForConfigurableProduct($product);
        }

        return $product;
    }
}
