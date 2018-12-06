<?php

use Magento\TestFramework\Helper\Bootstrap;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * @var SearchCriteriaBuilder $searchCriteriaBuilder
*/
$searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);

/**
 * @var \Magento\Cms\Api\PageRepositoryInterface $pageRepository
*/
$pageRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\Cms\Api\PageRepositoryInterface::class);

$searchCriteria = $searchCriteriaBuilder->addFilter(
    \Magento\Cms\Api\Data\PageInterface::IDENTIFIER,
    ['page-a'],
    'eq'
)->create();

$pages = $pageRepository->getList($searchCriteria);
if (!empty($pages)) {
    foreach ($pages->getItems() as $page) {
        $pageRepository->delete($page);
    }
}

/**
 * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection $urlRewriteCollection
*/
$urlRewriteCollection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection::class);
$collection = $urlRewriteCollection
    ->addFieldToFilter('request_path', ['page-a', 'page-one/', 'category-one'])
    ->load()
    ->walk('delete');
