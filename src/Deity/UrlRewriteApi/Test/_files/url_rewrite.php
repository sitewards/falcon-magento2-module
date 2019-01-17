<?php

use \Magento\UrlRewrite\Model\OptionProvider;
use \Magento\UrlRewrite\Model\UrlRewrite;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * @var UrlRewrite $rewrite
*/
/**
 * @var \Magento\Framework\ObjectManagerInterface $objectManager
*/
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/**
 * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite $rewriteResource
*/
$rewriteResource = $objectManager->create(
    \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite::class
);
/**
 * @var \Magento\Cms\Model\ResourceModel\Page $pageResource
*/
$pageResource = $objectManager->create(
    \Magento\Cms\Model\ResourceModel\Page::class
);



/**
 * @var $page \Magento\Cms\Model\Page
*/
$page = Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Cms Page A')
    ->setIdentifier('page-a')
    ->setIsActive(1)
    ->setContent('<h1>Cms Page A</h1>')
    ->setPageLayout('1column')
    ->setStores([1]);
$pageResource->save($page);

$rewrite = $objectManager->create(UrlRewrite::class);
$rewrite->setEntityType('custom')
    ->setRequestPath('page-one/')
    ->setTargetPath('page-a/')
    ->setRedirectType(OptionProvider::PERMANENT)
    ->setStoreId(1)
    ->setDescription('From page-one/ to page-a/');
$rewriteResource->save($rewrite);

$rewrite = $objectManager->create(UrlRewrite::class);
$rewrite->setEntityType('category')
    ->setRequestPath('category-one')
    ->setTargetPath('catalog/category/view/id/1')
    ->setStoreId(1)
    ->setDescription('test category 1');
$rewriteResource->save($rewrite);
