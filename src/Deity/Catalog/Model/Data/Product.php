<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Product
 *
 * @package Deity\Catalog\Model\Data
 */
class Product extends AbstractExtensibleModel implements ProductInterface
{

    /**
     * @var ProductPriceInterface
     */
    private $priceObject;

    /**
     * @inheritdoc
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku(string $sku): ProductInterface
    {
        $this->setData(self::SKU, $sku);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name): ProductInterface
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImage(): string
    {
        return (string)$this->getData(self::IMAGE);
    }

    /**
     * @inheritdoc
     */
    public function setImage(string $image): ProductInterface
    {
        $this->setData(self::IMAGE, $image);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUrlPath(): string
    {
        return (string)$this->getData(self::URL_PATH);
    }

    /**
     * @inheritdoc
     */
    public function setUrlPath(string $urlPath): ProductInterface
    {
        $this->setData(self::URL_PATH, $urlPath);
        return $this;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function setExtensionAttributes(
        \Deity\CatalogApi\Api\Data\ProductExtensionInterface $extensionAttributes
    ): ProductInterface {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIsSalable(): int
    {
        return (int)$this->getData(self::IS_SALABLE);
    }

    /**
     * @inheritdoc
     */
    public function setIsSalable(int $salableFlag): ProductInterface
    {
        $this->setData(self::IS_SALABLE, $salableFlag);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): ProductPriceInterface
    {
        return $this->priceObject;
    }

    /**
     * @inheritdoc
     */
    public function setPrice(ProductPriceInterface $productPrice): ProductInterface
    {
        $this->priceObject = $productPrice;
        return $this;
    }
}
