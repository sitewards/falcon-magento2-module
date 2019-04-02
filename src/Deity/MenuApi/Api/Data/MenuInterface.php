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
     * Get Name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set Name
     *
     * @param string $name
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setName(string $name): MenuInterface;

    /**
     * Get Id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Strict typing ommited to comply to AbstractModel
     *
     * @param int $id
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
     * Set Css Class
     *
     * @param string $cssClass
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setCssClass(string $cssClass): MenuInterface;

    /**
     * Get Url Path
     *
     * @return string
     */
    public function getUrlPath(): string;

    /**
     * Set Url Path
     *
     * @param string $urlPath
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setUrlPath(string $urlPath): MenuInterface;
    
    /**
     * Get Children menu nodes
     *
     * @return \Deity\MenuApi\Api\Data\MenuInterface[]
     */
    public function getChildren(): array;

    /**
     * Set Childnen menu nodes
     *
     * @param \Deity\MenuApi\Api\Data\MenuInterface[] $children
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setChildren(array $children): MenuInterface;

    /**
     * Get extension Attributes
     *
     * @return \Deity\MenuApi\Api\Data\MenuExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \Deity\MenuApi\Api\Data\MenuExtensionInterface $extensionAttributes
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setExtensionAttributes(MenuExtensionInterface $extensionAttributes): MenuInterface;
}
