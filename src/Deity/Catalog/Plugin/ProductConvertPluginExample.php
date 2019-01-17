<?php
declare(strict_types=1);

namespace Deity\Catalog\Plugin;

use Deity\CatalogApi\Api\Data\ProductInterface;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductConvertPluginExample
 * @package Deity\Catalog\Plugin
 */
class ProductConvertPluginExample
{
    /**
     * @param ProductConvertInterface $productConvertObject
     * @param ProductInterface $deityProduct
     * @return ProductInterface
     */
    public function afterConvert(ProductConvertInterface $productConvertObject, ProductInterface $deityProduct)
    {
        /**
         * Instance of magento product that is currently being processed by converter
         * @var Product $magentoProduct
         */
        $magentoProduct = $productConvertObject->getCurrentProduct();

        /**
         * Target object that could be generically extended using `extension_attributes.xml`
         */
        $attributes = $deityProduct->getExtensionAttributes();

        $attributes->setUrlKey($magentoProduct->getData('url_key'));

        return $deityProduct;
    }
}
