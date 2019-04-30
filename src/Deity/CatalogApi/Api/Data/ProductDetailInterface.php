<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductDetailInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductDetailInterface extends ExtensibleDataInterface
{
    const ID_FIELD_KEY = 'id';
    const SKU_FIELD_KEY = 'sku';
    const NAME_FIELD_KEY = 'name';
    const URL_PATH_FIELD_KEY = 'url_path';
    const IMAGE_FIELD_KEY = 'image';
    const IMAGE_RESIZED_FIELD_KEY = 'image_resized';
    const TYPE_ID_FIELD_KEY = 'type_id';
    const IS_SALABLE_FIELD_KEY = 'is_salable';
    const MEDIA_GALLERY_FIELD_KEY = 'media_gallery_sizes';
    const PRICE_FIELD_KEY = 'price';
    const TIER_PRICES_FIELD_KEY = 'tier_prices';
    const STOCK_FIELD_KEY = 'stock';
    const PRODUCT_LINKS_FIELD_KEY = 'product_links';
    const OPTIONS_FIELD_KEY = 'options';

    /**
     * Get product id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get product price object
     *
     * @return \Deity\CatalogApi\Api\Data\ProductPriceInterface
     */
    public function getPrice(): ProductPriceInterface;

    /**
     * Get product sku
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Get product name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get full size product image url
     *
     * @return string
     */
    public function getImage(): string;

    /**
     * Get resized product image
     *
     * @return string
     */
    public function getImageResized(): string;

    /**
     * Get product type id
     *
     * @return string
     */
    public function getTypeId(): string;

    /**
     * Get if product is salable
     *
     * @return int
     */
    public function getIsSalable(): int;

    /**
     * Get media AutocompleteItemExtensionInterfacegallery items
     *
     * @return \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[]
     */
    public function getMediaGallerySizes(): array;

    /**
     * Get product url path
     *
     * @return string
     */
    public function getUrlPath(): string;

    /**
     * Gets list of product tier prices
     *
     * @return \Magento\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices();

    /**
     * Get stock info
     *
     * @return \Deity\CatalogApi\Api\Data\ProductStockInterface
     */
    public function getStock(): ProductStockInterface;

    /**
     * Get product links
     *
     * @return \Magento\Catalog\Api\Data\ProductLinkInterface[]
     */
    public function getProductLinks(): array;

    /**
     * Get product options
     *
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]|null
     */
    public function getOptions(): array;

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductDetailInterface
     */
    public function setExtensionAttributes(
        ProductDetailExtensionInterface $extensionAttributes
    ): ProductDetailInterface;
}
