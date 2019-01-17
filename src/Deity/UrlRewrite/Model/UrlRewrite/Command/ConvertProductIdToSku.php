<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model\UrlRewrite\Command;

use Deity\UrlRewriteApi\Api\ConvertEntityIdToUniqueKeyInterface;
use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\Exception\IntegrationException;

/**
 * Class ConvertProductIdToSku
 * @package Deity\UrlRewrite\Model\UrlRewrite\Command
 */
class ConvertProductIdToSku implements ConvertEntityIdToUniqueKeyInterface
{

    /**
     * @var Product
     */
    private $productResourceModel;

    /**
     * ConvertProductIdToSku constructor.
     * @param Product $productResourceModel
     */
    public function __construct(Product $productResourceModel)
    {
        $this->productResourceModel = $productResourceModel;
    }

    /**
     * @param UrlRewriteInterface $rewrite
     * @return void
     * @throws IntegrationException
     */
    public function execute(UrlRewriteInterface $rewrite): void
    {
        $productId = $rewrite->getEntityId();
        $result = $this->productResourceModel->getProductsSku([$productId]);
        if (empty($result)) {
            throw new IntegrationException(__('Given product id (%1) have no sku in entity table', $productId));
        }
        $sku = $result[0]['sku'];
        $rewrite->setEntityId($sku);
    }
}
