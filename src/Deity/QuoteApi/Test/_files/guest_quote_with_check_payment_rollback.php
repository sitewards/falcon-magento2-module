<?php
require __DIR__ . '/../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/default_rollback.php';

/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$objectManager->get(\Magento\Framework\Registry::class)->unregister('quote');
$quote = $objectManager->create(\Magento\Quote\Model\Quote::class);
$quote->load('test_order_1', 'reserved_order_id')->delete();
