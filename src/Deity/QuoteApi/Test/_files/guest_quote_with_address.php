<?php

require __DIR__ . '/../../../../../../dev/tests/integration/testsuite/Magento/Catalog/_files/products.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Quote\Model\Quote\Address $quoteShippingAddress */
$quoteShippingAddress = $objectManager->create(\Magento\Quote\Model\Quote\Address::class);

$quoteShippingAddress->setCountryId('NL');
$quoteShippingAddress->setCity('Rotterdam');
$quoteShippingAddress->setFirstname('John');
$quoteShippingAddress->setLastname('Doe');
$quoteShippingAddress->setTelephone('123123123');
$quoteShippingAddress->setEmail('aaa@aaa.com');
$quoteShippingAddress->setPostcode('1233AS');
$quoteShippingAddress->setStreet('Some street 1');
$quoteShippingAddress->setSameAsBilling(true);

/** @var \Magento\Quote\Model\Quote $quote */
$quote = $objectManager->create(\Magento\Quote\Model\Quote::class);
$quote->setStoreId(
    1
)->setIsActive(
    true
)->setIsMultiShipping(
    false
)->setCustomerIsGuest(
    true
)->setCheckoutMethod(
    'guest'
)->setReservedOrderId(
    'test_order_1'
)->setBillingAddress(
    $quoteShippingAddress
)->setCustomerEmail(
    'aaa@aaa.com'
)->setShippingAddress(
    $quoteShippingAddress
)->addProduct(
    $product->load($product->getId()),
    2
);
