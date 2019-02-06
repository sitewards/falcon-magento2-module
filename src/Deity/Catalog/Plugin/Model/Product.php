<?php
declare(strict_types=1);

namespace Deity\Catalog\Plugin\Model;

use Deity\CatalogApi\Api\MediaGalleryProviderInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Model\Product as MagentoProduct;

/**
 * Class Product
 *
 * @package Deity\Catalog\Plugin\Model
 */
class Product
{
    /**
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * @var MediaGalleryProviderInterface
     */
    private $mediaGalleryProvider;

    /**
     * Product constructor.
     * @param ProductImageProviderInterface $imageProvider
     * @param MediaGalleryProviderInterface $mediaGalleryProvider
     */
    public function __construct(
        ProductImageProviderInterface $imageProvider,
        MediaGalleryProviderInterface $mediaGalleryProvider
    ) {
        $this->imageProvider = $imageProvider;
        $this->mediaGalleryProvider = $mediaGalleryProvider;
    }

    /**
     * Add resized image information to the product's extension attributes.
     *
     * @param MagentoProduct $product
     * @return MagentoProduct
     */
    public function afterLoad(MagentoProduct $product)
    {

        $productExtension = $product->getExtensionAttributes();

        $mainImage = $this->imageProvider->getProductImageTypeUrl($product, 'product_page_image_large');
        $productExtension->setData('thumbnail_url', $mainImage);
        $thumbUrl = $this->imageProvider->getProductImageTypeUrl($product, 'product_list_thumbnail');
        $productExtension->setData('thumbnail_resized_url', $thumbUrl);

        $mediaGalleryInfo = $this->mediaGalleryProvider->getMediaGallerySizes($product);

        $productExtension->setMediaGallerySizes($mediaGalleryInfo);

        $product->setExtensionAttributes($productExtension);
        return $product;
    }
}
