<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* Create attribute */
/** @var $attribute \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
$attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Catalog\Model\ResourceModel\Eav\Attribute::class
);

/** @var $installer \Magento\Catalog\Setup\CategorySetup */
$installer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Catalog\Setup\CategorySetup::class
);

$attribute->setData(
    [
        'attribute_code'                => 'filterable_attribute',
        'entity_type_id'                => $installer->getEntityTypeId('catalog_product'),
        'is_global'                     => 0,
        'is_user_defined'               => 1,
        'frontend_input'                => 'select',
        'is_unique'                     => 0,
        'is_required'                   => 0,
        'is_searchable'                 => 0,
        'is_visible_in_advanced_search' => 0,
        'is_comparable'                 => 0,
        'is_filterable'                 => 1,
        'is_filterable_in_search'       => 0,
        'is_used_for_promo_rules'       => 0,
        'is_html_allowed_on_front'      => 1,
        'is_visible_on_front'           => 1,
        'used_in_product_listing'       => 1,
        'used_for_sort_by'              => 0,
        'frontend_label'                => ['Filterable Attribute'],
        'backend_type'                  => 'int',
        'backend_model'                 => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
        'option'                        => [
            'value' => [
                'option_1' => ['Option 1'],
                'option_2' => ['Option 2']
            ],
            'order' => [
                'option_1' => 1,
                'option_2' => 2
            ],
        ],
    ]
);
$attribute->save();

/* Assign attribute to attribute set */
$installer->addAttributeToGroup('catalog_product', 'Default', 'Attributes', $attribute->getId());
