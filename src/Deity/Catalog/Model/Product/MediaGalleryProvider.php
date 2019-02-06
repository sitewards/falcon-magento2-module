<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface;
use Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterfaceFactory;
use Deity\CatalogApi\Api\MediaGalleryProviderInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Gallery\ReadHandler;

/**
 * Class MediaGalleryProvider
 *
 * @package Deity\Catalog\Model\Product
 */
class MediaGalleryProvider implements MediaGalleryProviderInterface
{

    /**
     * @var ReadHandler
     */
    private $galleryReadHandler;

    /**
     * @var GalleryMediaEntrySizeInterfaceFactory
     */
    private $galleryMediaEntrySizeFactory;

    /**
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * MediaGalleryProvider constructor.
     * @param ReadHandler $galleryReadHandler
     * @param GalleryMediaEntrySizeInterfaceFactory $galleryMediaEntrySizeFactory
     * @param ProductImageProviderInterface $imageProvider
     */
    public function __construct(
        ReadHandler $galleryReadHandler,
        GalleryMediaEntrySizeInterfaceFactory $galleryMediaEntrySizeFactory,
        ProductImageProviderInterface $imageProvider
    ) {
        $this->galleryReadHandler = $galleryReadHandler;
        $this->galleryMediaEntrySizeFactory = $galleryMediaEntrySizeFactory;
        $this->imageProvider = $imageProvider;
    }

    /**
     * @inheritdoc
     */
    public function getMediaGallerySizes(ProductInterface $product): array
    {

        $this->galleryReadHandler->execute($product);

        $sizes = [];
        /** @var ProductAttributeMediaGalleryEntryInterface $mediaGalleryEntries */
        $mediaGalleryEntries = $product->getMediaGalleryEntries();
        if (!$mediaGalleryEntries) {
            return $sizes;
        }

        foreach ($mediaGalleryEntries as $mediaGalleryEntry) {
            if (!$this->isValidMediaGalleryEntry($mediaGalleryEntry)) {
                continue;
            }

            $file = $mediaGalleryEntry->getFile();

            $galleryItem[GalleryMediaEntrySizeInterface::THUMBNAIL] =
                $this->imageProvider->getProductImageTypeUrl($product, 'product_media_gallery_item_thumbnail', $file);
            $galleryItem[GalleryMediaEntrySizeInterface::FULL] =
                $this->imageProvider->getProductImageTypeUrl($product, 'product_media_gallery_item', $file);
            $galleryItem[GalleryMediaEntrySizeInterface::TYPE] = $mediaGalleryEntry->getMediaType();
            $galleryItem[GalleryMediaEntrySizeInterface::EMBED_URL] = '';
            if ($mediaGalleryEntry->getMediaType() === 'external-video') {
                $galleryItem[GalleryMediaEntrySizeInterface::EMBED_URL] =
                    $this->mediaHelper->getProductVideoUrl($product, $mediaGalleryEntry->getId());
            }
            $sizes[] = $this->galleryMediaEntrySizeFactory->create($galleryItem);
        }

        return $sizes;
    }

    /**
     * Validate if media entry can be included in gallery
     *
     * @param ProductAttributeMediaGalleryEntryInterface $entity
     * @return bool
     */
    private function isValidMediaGalleryEntry(ProductAttributeMediaGalleryEntryInterface $entity): bool
    {
        if ($entity->isDisabled()) {
            return false;
        }

        return true;
    }
}
