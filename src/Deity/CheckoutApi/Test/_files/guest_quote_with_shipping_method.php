<?php

require __DIR__ . '/../../../QuoteApi/Test/_files/guest_quote_with_address.php';

$quote->load('test_order_1', 'reserved_order_id');
$shippingAddress = $quote->getShippingAddress();
$shippingAddress->setShippingMethod('flatrate_flatrate')
    ->setShippingDescription('Flat Rate - Fixed')
    ->setShippingAmount(10.0)
    ->setBaseShippingAmount(12.0)
    ->save();

$quote->collectTotals();
$quote->save();

/** @var \Magento\Quote\Model\QuoteIdMask $quoteIdMask */
$quoteIdMask = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\Quote\Model\QuoteIdMaskFactory::class)
    ->create();
$quoteIdMask->setQuoteId($quote->getId());
$quoteIdMask->setDataChanges(true);
$quoteIdMask->save();
