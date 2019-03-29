<?php
declare(strict_types=1);

namespace Deity\Menu\Model;

use Deity\MenuApi\Api\Data\MenuInterface;
use Deity\MenuApi\Api\Data\MenuInterfaceFactory;
use Deity\MenuApi\Model\ConvertCategoryToMenuInterface;
use Magento\Catalog\Model\Category;

/**
 * Class ConvertCategoryToMenu
 *
 * @package Deity\Menu\Model
 */
class ConvertCategoryToMenu implements ConvertCategoryToMenuInterface
{

    /**
     * @var MenuInterfaceFactory
     */
    private $menuFactory;

    /**
     * ConvertCategoryToMenu constructor.
     *
     * @param MenuInterfaceFactory $menuFactory
     */
    public function __construct(MenuInterfaceFactory $menuFactory)
    {
        $this->menuFactory = $menuFactory;
    }

    /**
     * Convert category object to menu interface
     *
     * @param Category $category
     * @return MenuInterface
     */
    public function execute(Category $category): MenuInterface
    {
        /** @var MenuInterface $menuItem */
        $menuItem = $this->menuFactory->create();
        $menuItem->setId((int)$category->getId());
        $menuItem->setUrlPath((string)$category->getRequestPath());
        $menuItem->setName((string)$category->getName());

        return $menuItem;
    }
}
