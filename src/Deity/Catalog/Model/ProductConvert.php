<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\ProductInterface as DeityProductInterface;
use Deity\CatalogApi\Api\Data\ProductInterfaceFactory;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Deity\CatalogApi\Api\ProductPriceProviderInterface;
use Deity\CatalogApi\Model\ProductUrlPathProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Profiler;

/**
 * Class ProductConvert
 *
 * @package Deity\Catalog\Model
 */
class ProductConvert implements ProductConvertInterface
{

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var ProductInterface
     */
    private $currentProductObject;

    /**
     * @var ProductUrlPathProviderInterface
     */
    private $urlPathProvider;

    /**
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * @var ProductPriceProviderInterface
     */
    private $priceProvider;

    /**
     * ProductConvert constructor.
     * @param ProductInterfaceFactory $productFactory
     * @param ProductUrlPathProviderInterface $urlPathProvider
     * @param ProductPriceProviderInterface $priceProvider
     * @param ProductImageProviderInterface $imageProvider
     */
    public function __construct(
        ProductInterfaceFactory $productFactory,
        ProductUrlPathProviderInterface $urlPathProvider,
        ProductPriceProviderInterface $priceProvider,
        ProductImageProviderInterface $imageProvider
    ) {
        $this->urlPathProvider = $urlPathProvider;
        $this->priceProvider = $priceProvider;
        $this->imageProvider = $imageProvider;
        $this->productFactory = $productFactory;
    }

    /**
     * @inheritdoc
     */
    public function convert(Product $product): DeityProductInterface
    {
        Profiler::start('__PRODUCT_LISTING_CONVERT__', ['group' => 'Deity']);

        $this->currentProductObject = $product;

        $deityProduct = $this->productFactory->create();
        $deityProduct->setName($product->getName());
        $deityProduct->setSku($product->getSku());
        $deityProduct->setUrlPath(
            $this->urlPathProvider->getProductUrlPath($product, (string)$product->getCategoryId())
        );
        $deityProduct->setIsSalable((int)$product->getIsSalable());

        $deityProduct->setImage(
            $this->imageProvider->getProductImageTypeUrl($product, 'deity_category_page_list')
        );

        Profiler::start('__PRODUCT_LISTING_CONVERT_PRICE_CALC__', ['group' => 'Deity']);
        $deityProduct->setPrice(
            $this->priceProvider->getPriceData($product)
        );
        Profiler::stop('__PRODUCT_LISTING_CONVERT_PRICE_CALC__');

        Profiler::stop('__PRODUCT_LISTING_CONVERT__');
        return $deityProduct;
    }

    /**
     * @inheritdoc
     */
    public function getCurrentProduct(): ProductInterface
    {
        return $this->currentProductObject;
    }
}
