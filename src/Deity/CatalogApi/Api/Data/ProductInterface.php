<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface Product
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductInterface extends ExtensibleDataInterface
{
    const SKU = 'sku';

    const NAME = 'name';

    const IMAGE = 'image';

    const URL_PATH = 'url_path';

    const IS_SALABLE = 'is_salable';

    /**
     * Get price
     *
     * @return \Deity\CatalogApi\Api\Data\ProductPriceInterface
     */
    public function getPrice(): ProductPriceInterface;

    /**
     * Set price
     *
     * @param \Deity\CatalogApi\Api\Data\ProductPriceInterface $productPrice
     * @return  \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setPrice(ProductPriceInterface $productPrice): ProductInterface;

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setExtensionAttributes(ProductExtensionInterface $extensionAttributes): ProductInterface;

    /**
     * Get is salable
     *
     * @return int
     */
    public function getIsSalable(): int;

    /**
     * Set is salable
     *
     * @param int $salableFlag
     * @return ProductInterface
     */
    public function setIsSalable(int $salableFlag): ProductInterface;

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set sku
     *
     * @param string $sku
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setSku(string $sku): ProductInterface;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set name
     *
     * @param string $name
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setName(string $name): ProductInterface;

    /**
     * Get image
     *
     * @return string
     */
    public function getImage(): string;

    /**
     * Set image
     *
     * @param string $image
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setImage(string $image): ProductInterface;

    /**
     * Get url path
     *
     * @return string
     */
    public function getUrlPath(): string;

    /**
     * Set url path
     *
     * @param string $urlPath
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setUrlPath(string $urlPath): ProductInterface;
}
