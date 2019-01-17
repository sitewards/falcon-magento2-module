<?php
declare(strict_types=1);

namespace Deity\MenuApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Menu interface
 *
 * @package Deity\MenuApi\Api\Data
 */
interface MenuInterface extends ExtensibleDataInterface
{
    const ID = 'id';
    const CHILDREN = 'children';
    const NAME = 'name';
    const URL_PATH = 'url_path';
    const CSS_CLASS = 'css_class';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setName(string $name): MenuInterface;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * Strict typing ommited to comply to AbstractModel
     *
     * @param $id
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setId($id): MenuInterface;

    /**
     * New Field for magento to provide special css-class that can be used on Frontend
     *
     * @return string
     */
    public function getCssClass(): string;

    /**
     * @param string $cssClass
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setCssClass(string $cssClass): MenuInterface;

    /**
     * @return string
     */
    public function getUrlPath(): string;

    /**
     * @param string $urlPath
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setUrlPath(string $urlPath): MenuInterface;
    
    /**
     * @return \Deity\MenuApi\Api\Data\MenuInterface[]
     */
    public function getChildren(): array;

    /**
     * @param \Deity\MenuApi\Api\Data\MenuInterface[] $children
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setChildren(array $children): MenuInterface;

    /**
     * @return \Deity\MenuApi\Api\Data\MenuExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param \Deity\MenuApi\Api\Data\MenuExtensionInterface $extensionAttributes
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setExtensionAttributes(MenuExtensionInterface $extensionAttributes): MenuInterface;
}
