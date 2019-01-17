<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface MediaGalleryProvider
 * @package Deity\CatalogApi\Api
 */
interface MediaGalleryProviderInterface
{
    /**
     * @param ProductInterface $product
     * @return \Deity\Catalog\Model\Data\GalleryMediaEntrySize[]
     */
    public function getMediaGallerySizes(ProductInterface $product): array;
}
