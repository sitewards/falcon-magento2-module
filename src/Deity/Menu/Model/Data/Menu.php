<?php
declare(strict_types=1);

namespace Deity\Menu\Model\Data;

use Deity\MenuApi\Api\Data\MenuExtensionInterface;
use Deity\MenuApi\Api\Data\MenuInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Menu
 *
 * @package Deity\Menu\Model\Data
 */
class Menu extends AbstractExtensibleModel implements MenuInterface
{

    /**
     * @var MenuInterface[]
     */
    private $children = [];

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string)$this->_getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return (string)$this->_getData(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($value): MenuInterface
    {
        $this->setData(self::ID, $value);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name): MenuInterface
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUrlPath(): string
    {
        return (string)$this->_getData(self::URL_PATH);
    }

    /**
     * @inheritdoc
     */
    public function setUrlPath(string $urlPath): MenuInterface
    {
        $this->setData(self::URL_PATH, $urlPath);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCssClass(): string
    {
        return (string)$this->_getData(self::CSS_CLASS);
    }

    /**
     * @inheritdoc
     */
    public function setCssClass(string $cssClass): MenuInterface
    {
        $this->setData(self::CSS_CLASS, $cssClass);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function setChildren(array $children): MenuInterface
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(MenuInterface::class);
            $this->_setExtensionAttributes($extensionAttributes);
            return $extensionAttributes;
        }
        return $extensionAttributes;
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(MenuExtensionInterface $extensionAttributes): MenuInterface
    {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }
}
