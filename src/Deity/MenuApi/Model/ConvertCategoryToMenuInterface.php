<?php
declare(strict_types=1);

namespace Deity\MenuApi\Model;

use Deity\MenuApi\Api\Data\MenuInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\Validation\ValidationException;

/**
 * Interface ConvertCategoryToMenuItem
 *
 * @package Deity\MenuApi\Model
 */
interface ConvertCategoryToMenuInterface
{
    /**
     * Convert category object to menu interface
     *
     * @param Category $category
     * @return MenuInterface
     */
    public function execute(Category $category): MenuInterface;
}
