<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\ProductInterface as DeityProductInterface;
use Deity\CatalogApi\Api\Data\ProductInterfaceFactory;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Deity\CatalogApi\Api\ProductPriceProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Profiler;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class ProductConvert
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
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    private $urlFinder;

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
     * @param UrlFinderInterface $urlFinder
     * @param ProductPriceProviderInterface $priceProvider
     * @param ProductImageProviderInterface $imageProvider
     */
    public function __construct(
        ProductInterfaceFactory $productFactory,
        UrlFinderInterface $urlFinder,
        ProductPriceProviderInterface $priceProvider,
        ProductImageProviderInterface $imageProvider
    ) {
        $this->priceProvider = $priceProvider;
        $this->imageProvider = $imageProvider;
        $this->urlFinder = $urlFinder;
        $this->productFactory = $productFactory;
    }

    /**
     * Convert product to array representation
     *
     * @param Product $product
     * @return DeityProductInterface
     * @throws LocalizedException
     */
    public function convert(Product $product): DeityProductInterface
    {
        Profiler::start('__PRODUCT_LISTING_CONVERT__', ['group' => 'Deity']);

        $this->currentProductObject = $product;

        $deityProduct = $this->productFactory->create();
        $deityProduct->setName($product->getName());
        $deityProduct->setSku($product->getSku());
        $deityProduct->setUrlPath($this->getProductUrlPath($product));
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
     * @param Product $product
     * @return string
     * @throws LocalizedException
     */
    private function getProductUrlPath(Product $product): string
    {
        $filterData = [
            UrlRewrite::ENTITY_ID => $product->getId(),
            UrlRewrite::ENTITY_TYPE => \Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator::ENTITY_TYPE,
            UrlRewrite::STORE_ID => $product->getStoreId(),
        ];

        $filterData[UrlRewrite::METADATA]['category_id'] = $product->getCategoryId();

        $rewrite = $this->urlFinder->findOneByData($filterData);

        if ($rewrite) {
            return  $rewrite->getRequestPath();
        }

        // try to get direct url to magento
        unset($filterData[UrlRewrite::METADATA]);

        $rewrite = $this->urlFinder->findOneByData($filterData);

        if ($rewrite) {
            return  $rewrite->getRequestPath();
        }

        throw new LocalizedException(__('Unable to get seo friendly url for product'));
    }

    /**
     * @return ProductInterface
     */
    public function getCurrentProduct(): ProductInterface
    {
        return $this->currentProductObject;
    }
}
