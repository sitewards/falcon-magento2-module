<?php
declare(strict_types=1);
require __DIR__ . '/../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/order.php';


/** @var \Deity\SalesApi\Api\OrderIdMaskRepositoryInterface $orderIdMaskRepository */
$orderIdMaskRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Deity\SalesApi\Api\OrderIdMaskRepositoryInterface::class);

$orderIdMaskRepository->create((int)$order->getEntityId());
