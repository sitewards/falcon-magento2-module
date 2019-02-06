<?php
/**
 * Created by Ryan Copeland <ryan@ryancopeland.co.uk>.
 * User: ryancopeland
 * Date: 2019-01-05
 * Time: 15:50
 */

namespace Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider;

use Deity\UrlRewriteApi\Api\CanonicalUrlProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class ProductUrlProvider
 *
 * @package Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider
 */
class ProductUrlProvider implements CanonicalUrlProviderInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ProductUrlProvider constructor.
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    public function getCanonicalUrl(UrlRewrite $urlModel)
    {
        try {
            $product = $this->productRepository->getById($urlModel->getEntityId());
        } catch (NoSuchEntityException $e) {
            return '';
        }

        return $product->getUrlModel()->getUrl($product, ['_ignore_category' => true]);
    }
}
