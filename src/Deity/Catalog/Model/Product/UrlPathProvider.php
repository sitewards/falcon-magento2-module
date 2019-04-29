<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Model\ProductUrlPathProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class UrlPathProvider
 *
 * @package Deity\Catalog\Model\Product
 */
class UrlPathProvider implements ProductUrlPathProviderInterface
{

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * UrlPathProvider constructor.
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(UrlFinderInterface $urlFinder)
    {
        $this->urlFinder = $urlFinder;
    }
    
    /**
     * Get product url path
     *
     * @param Product $product
     * @param string|null $categoryId
     * @return string
     * @throws LocalizedException
     */
    public function getProductUrlPath(Product $product, ?string $categoryId = ''): string
    {
        $filterData = [
            UrlRewrite::ENTITY_ID => $product->getId(),
            UrlRewrite::ENTITY_TYPE => \Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator::ENTITY_TYPE,
            UrlRewrite::STORE_ID => $product->getStoreId(),
        ];
        if ($categoryId !== null && $categoryId !== '') {
            $filterData[UrlRewrite::METADATA]['category_id'] = $categoryId;
        }

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
}
