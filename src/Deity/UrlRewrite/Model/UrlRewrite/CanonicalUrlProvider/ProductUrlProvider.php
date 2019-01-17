<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider;

use Deity\UrlRewriteApi\Api\CanonicalUrlProviderInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class ProductUrlProvider implements CanonicalUrlProviderInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ProductUrlProvider constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @param $urlModel UrlRewrite
     * @return string
     */
    public function getCanonicalUrl(UrlRewrite $urlModel): string
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->getById($urlModel->getEntityId());
        } catch (NoSuchEntityException $e) {
            return '';
        }

        return $product->getUrlModel()->getUrl($product, ['_ignore_category' => true]);
    }
}
