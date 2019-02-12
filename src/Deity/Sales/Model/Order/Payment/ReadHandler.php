<?php
declare(strict_types=1);
namespace Deity\Sales\Model\Order\Payment;

use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Sales\Api\Data\OrderPaymentExtensionInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ReadHandler
 *
 * @package Deity\Sales\Model\Order\Payment
 */
class ReadHandler implements ExtensionInterface
{
    /** @var ExtensionAttributesFactory */
    private $extensionAttributesFactory;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * Extension constructor.
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ExtensionAttributesFactory $extensionAttributesFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get order payment extension attribute
     *
     * @param OrderPaymentInterface $orderPayment
     * @return OrderPaymentExtensionInterface
     */
    private function getOrderPaymentExtensionAttribute(OrderPaymentInterface $orderPayment)
    {
        $extensionAttributes = $orderPayment->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(OrderPaymentInterface::class);
        }

        return $extensionAttributes;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param OrderPaymentInterface $entity
     * @param array $arguments
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        $extensionAttributes = $this->getOrderPaymentExtensionAttribute($entity);
        $extensionAttributes->setMethodName(
            $this->scopeConfig->getValue("payment/{$entity->getMethod()}/title"),
            ScopeInterface::SCOPE_STORES,
            $entity->getOrder() ? $entity->getOrder()->getStoreId() : null
        );

        $entity->setExtensionAttributes($extensionAttributes);
    }
}
