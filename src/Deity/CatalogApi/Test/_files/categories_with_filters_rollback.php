<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var \Magento\Framework\Registry $registry */
$registry = $objectManager->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

// Remove products
/** @var \Magento\Catalog\Api\ProductRepositoryInterface $productRepository */
$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$productsToDelete = ['simple-1', 'simple-2'];

foreach ($productsToDelete as $sku) {
    try {
        $product = $productRepository->get($sku, false, null, true);
        $productRepository->delete($product);
    } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
        //Product already removed
    }
}

//Remove categories
/** @var Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
$collection = $objectManager->create(\Magento\Catalog\Model\ResourceModel\Category\Collection::class);
foreach ($collection->addAttributeToFilter('level', ['in' => [2, 3]]) as $category) {
    /** @var \Magento\Catalog\Model\Category $category */
    $category->delete();
}
$entityModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Eav\Model\Entity::class);
$entityTypeId = $entityModel->setType(\Magento\Catalog\Model\Product::ENTITY)->getTypeId();

/** @var $attribute \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
$attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Catalog\Model\ResourceModel\Eav\Attribute::class
);

$attribute->loadByCode($entityTypeId, 'filterable_attribute');
$attribute->delete();

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
