<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Product
 * @package Deity\Catalog\Model\Data
 */
class Product extends AbstractExtensibleModel implements ProductInterface
{

    /**
     * @var ProductPriceInterface
     */
    private $priceObject;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    /**
     * @param string $sku
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setSku(string $sku): ProductInterface
    {
        $this->setData(self::SKU, $sku);
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    /**
     * @param string $name
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setName(string $name): ProductInterface
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return (string)$this->getData(self::IMAGE);
    }

    /**
     * @param string $image
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setImage(string $image): ProductInterface
    {
        $this->setData(self::IMAGE, $image);
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlPath(): string
    {
        return (string)$this->getData(self::URL_PATH);
    }

    /**
     * @param string $urlPath
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setUrlPath(string $urlPath): ProductInterface
    {
        $this->setData(self::URL_PATH, $urlPath);
        return $this;
    }

    /**
     * @return \Deity\CatalogApi\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(ProductInterface::class);
            $this->_setExtensionAttributes($extensionAttributes);
            return $extensionAttributes;
        }
        return $extensionAttributes;
    }

    /**
     * @param \Deity\CatalogApi\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setExtensionAttributes(
        \Deity\CatalogApi\Api\Data\ProductExtensionInterface $extensionAttributes
    ): ProductInterface {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }

    /**
     * @return int
     */
    public function getIsSalable(): int
    {
        return (int)$this->getData(self::IS_SALABLE);
    }

    /**
     * @param int $salableFlag
     * @return ProductInterface
     */
    public function setIsSalable(int $salableFlag): ProductInterface
    {
        $this->setData(self::IS_SALABLE, $salableFlag);
        return $this;
    }

    /**
     * @return \Deity\CatalogApi\Api\Data\ProductPriceInterface
     */
    public function getPrice(): ProductPriceInterface
    {
        return $this->priceObject;
    }

    /**
     * @param \Deity\CatalogApi\Api\Data\ProductPriceInterface $productPrice
     * @return  \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setPrice(ProductPriceInterface $productPrice): ProductInterface
    {
        $this->priceObject = $productPrice;
        return $this;
    }
}
