<?php

namespace Deity\MagentoApi\Plugin\Catalog\Model;

use Deity\MagentoApi\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category as MagentoCategory;

class Category
{
    /** @var CategoryHelper */
    protected $categoryHelper;

    /**
     * @param CategoryHelper $categoryHelper
     */
    public function __construct(CategoryHelper $categoryHelper)
    {
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * @param MagentoCategory $category
     * @return MagentoCategory
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterLoad(MagentoCategory $category)
    {
        $this->categoryHelper->addImageAttribute($category);
        return $category;
    }
}