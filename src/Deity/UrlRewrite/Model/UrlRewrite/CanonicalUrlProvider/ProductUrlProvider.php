<?php
/**
 * Created by Ryan Copeland <ryan@ryancopeland.co.uk>.
 * User: ryancopeland
 * Date: 2019-01-05
 * Time: 15:50
 */

namespace Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider;

use Deity\UrlRewriteApi\Api\CanonicalUrlProviderInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class ProductUrlProvider
 *
 * @package Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider
 */
class ProductUrlProvider implements CanonicalUrlProviderInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * ProductUrlProvider constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    public function getCanonicalUrl(UrlRewrite $urlModel)
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->getById($urlModel->getEntityId());
        } catch (NoSuchEntityException $e) {
            return '';
        }

        $productUrl = $product->getUrlModel()->getUrl($product, ['_ignore_category' => true]);
        $storeUrl = $this->urlBuilder->getBaseUrl();
        return str_replace($storeUrl, '', $productUrl);
    }
}
