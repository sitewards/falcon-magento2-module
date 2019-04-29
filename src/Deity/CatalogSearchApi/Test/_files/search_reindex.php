<?php
$indexerFactory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Indexer\Model\IndexerFactory::class
);
/** @var \Magento\Indexer\Model\Indexer $indexer */
$indexer = $indexerFactory->create();
$indexer->load('catalogsearch_fulltext');
$indexer->reindexAll();
