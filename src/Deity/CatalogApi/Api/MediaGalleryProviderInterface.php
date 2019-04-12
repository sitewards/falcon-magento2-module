<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface MediaGalleryProvider
 *
 * @package Deity\CatalogApi\Api
 */
interface MediaGalleryProviderInterface
{
    /**
     * Get media gallery sizes
     *
     * @param ProductInterface $product
     * @return \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[]
     */
    public function getMediaGallerySizes(ProductInterface $product): array;
}
