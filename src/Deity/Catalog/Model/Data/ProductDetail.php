<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface;
use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Deity\CatalogApi\Api\Data\ProductStockInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

/**
 * Class ProductDetail
 *
 * @package Deity\Catalog\Model\Data
 */
class ProductDetail implements ProductDetailInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $imageResized;

    /**
     * @var int
     */
    private $isSalable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $typeId;

    /**
     * @var array
     */
    private $mediaGallery;

    /**
     * @var string
     */
    private $urlPath;

    /**
     * @var ProductPriceInterface
     */
    private $priceObject;

    /**
     * @var ProductStockInterface
     */
    private $stockObject;

    /**
     * @var \Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface[]
     */
    private $tierPrices;

    /**
     * @var \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]
     */
    private $options;

    /**
     * @var \Magento\Catalog\Api\Data\ProductLinkInterface[]
     */
    private $productLinks;

    /**
     * @var ProductDetailExtensionInterface
     */
    private $extensionAttributes;

    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * ProductDetail constructor.
     * @param int $id
     * @param string $image
     * @param string $image_resized
     * @param int $is_salable
     * @param string $name
     * @param string $sku
     * @param string $url_path
     * @param string $type_id
     * @param array $media_gallery_sizes
     * @param ProductPriceInterface $price
     * @param ProductStockInterface $stock
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param array $tier_prices
     * @param array $options
     * @param array $productLinks
     */
    public function __construct(
        int $id,
        string $image,
        string $image_resized,
        int $is_salable,
        string $name,
        string $sku,
        string $url_path,
        string $type_id,
        array $media_gallery_sizes,
        ProductPriceInterface $price,
        ProductStockInterface $stock,
        ExtensionAttributesFactory $extensionAttributesFactory,
        array $tier_prices,
        array $options = [],
        array $productLinks = []
    ) {
        $this->options = $options;
        $this->productLinks = $productLinks;
        $this->stockObject = $stock;
        $this->tierPrices = $tier_prices;
        $this->priceObject = $price;
        $this->urlPath = $url_path;
        $this->extensionAttributesFactory = $extensionAttributesFactory;
        $this->mediaGallery = $media_gallery_sizes;
        $this->id = $id;
        $this->image = $image;
        $this->imageResized = $image_resized;
        $this->isSalable = $is_salable;
        $this->name = $name;
        $this->sku = $sku;
        $this->typeId = $type_id;
    }

    /**
     * Get product sku
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get full size product image url
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Get resized product image
     *
     * @return string
     */
    public function getImageResized(): string
    {
        return $this->imageResized;
    }

    /**
     * Get product type id
     *
     * @return string
     */
    public function getTypeId(): string
    {
        return $this->typeId;
    }

    /**
     * Get if product is salable
     *
     * @return int
     */
    public function getIsSalable(): int
    {
        return $this->isSalable;
    }

    /**
     * Get product id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get media gallery items
     *
     * @return \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[]
     */
    public function getMediaGallerySizes(): array
    {
        return $this->mediaGallery;
    }

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface
     */
    public function getExtensionAttributes()
    {
        if (!$this->extensionAttributes) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(ProductDetailInterface::class);
        }

        return $this->extensionAttributes;
    }

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductDetailInterface
     */
    public function setExtensionAttributes(ProductDetailExtensionInterface $extensionAttributes): ProductDetailInterface
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }

    /**
     * Get product url path
     *
     * @return string
     */
    public function getUrlPath(): string
    {
        return $this->urlPath;
    }

    /**
     * Get product price object
     *
     * @return \Deity\CatalogApi\Api\Data\ProductPriceInterface
     */
    public function getPrice(): ProductPriceInterface
    {
        return $this->priceObject;
    }

    /**
     * Gets list of product tier prices
     *
     * @return \Magento\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices()
    {
        return $this->tierPrices;
    }

    /**
     * Get stock info
     *
     * @return ProductStockInterface
     */
    public function getStock(): ProductStockInterface
    {
        return $this->stockObject;
    }

    /**
     * Get product links
     *
     * @return \Magento\Catalog\Api\Data\ProductLinkInterface[]
     */
    public function getProductLinks(): array
    {
        return $this->productLinks;
    }

    /**
     * Get product options
     *
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]|null
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
