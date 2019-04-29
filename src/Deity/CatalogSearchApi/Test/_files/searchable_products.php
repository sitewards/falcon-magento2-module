<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $installer \Magento\Catalog\Setup\CategorySetup */
$installer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Catalog\Setup\CategorySetup::class
);

$attributeSetId = $installer->getAttributeSetId('catalog_product', 'Default');

$productRepository = $objectManager->create(
    \Magento\Catalog\Api\ProductRepositoryInterface::class
);


/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId($attributeSetId)
    ->setStoreId(1)
    ->setWebsiteIds([1])
    ->setName('Simple Shoe')
    ->setSku('simple-shoe')
    ->setPrice(10)
    ->setUrlKey('simple-shoe')
    ->setWeight(18)
    ->setData('')
    ->setStockData(['use_config_manage_stock' => 0])
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->save();

$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId($attributeSetId)
    ->setStoreId(1)
    ->setWebsiteIds([1])
    ->setName('Simple Shirt')
    ->setUrlKey('simple-shirt')
    ->setSku('simple-shirt') // SKU intentionally contains digits only
    ->setPrice(45.67)
    ->setWeight(56)
    ->setStockData(['use_config_manage_stock' => 0])
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->save();

/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId($attributeSetId)
    ->setStoreId(1)
    ->setWebsiteIds([1])
    ->setName('Simple Belt')
    ->setSku('simple-belt')
    ->setPrice(10)
    ->setUrlKey('simple-belt')
    ->setWeight(18)
    ->setData('')
    ->setStockData(['use_config_manage_stock' => 0])
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->save();
