<?php

namespace Deity\MagentoApi\Setup;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * @package Deity\MagentoApi\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        
        if(version_compare($context->getVersion(), '0.1.22') < 0) {
            $this->addProductShowOnHomePositionField($setup, $eavSetup);
        }

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param EavSetup $eavSetup
     */
    protected function addProductShowOnHomePositionField(ModuleDataSetupInterface $setup, EavSetup $eavSetup)
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'is_on_homepage',
            [
                'type' => 'int',
                'input' => 'boolean',
                'label' => 'On homepage',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'searchable' => true,
                'default' => 0,
                'filterable' => false,
                'is_filterable_in_grid' => false,
                'is_used_in_grid' => false,
                'is_searchable' => false,
                'is_visible_in_grid' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'user_defined' => true,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'sort_order' => 1,
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'homepage_sort_order',
            [
                'type' => 'int',
                'input' => 'text',
                'label' => 'Homepage Product Sort Order',
                'required' => false,
                'searchable' => true,
                'default' => 100,
                'filterable' => false,
                'is_filterable_in_grid' => false,
                'is_used_in_grid' => false,
                'is_searchable' => false,
                'is_visible_in_grid' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'user_defined' => true,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'sort_order' => 1,
            ]
        );

        foreach($eavSetup->getAllAttributeSetIds(Product::ENTITY) as $setId) {
            $eavSetup->addAttributeToGroup(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                $setId,
                'Product Details',
                'is_on_homepage',
                100
            );

            $eavSetup->addAttributeToGroup(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                $setId,
                'Product Details',
                'homepage_sort_order',
                101
            );
        }
    }
}
