<?php
declare(strict_types=1);

namespace Deity\Store\Plugin\Model;

use Magento\Store\Api\Data\StoreExtensionInterface;
use Magento\Store\Api\Data\StoreExtensionFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;

/**
 * Class StoreRepository
 *
 * @package Deity\Store\Plugin\Model
 */
class StoreRepository
{
    /**
     * @var StoreExtensionFactory
     */
    private $storeExtensionFactory;

    /**
     * StoreRepository constructor.
     * @param StoreExtensionFactory $storeExtensionFactory
     */
    public function __construct(StoreExtensionFactory $storeExtensionFactory)
    {
        $this->storeExtensionFactory = $storeExtensionFactory;
    }

    /**
     * After plugin for getList method
     *
     * @param StoreRepositoryInterface $subject
     * @param StoreInterface[] $result
     * @return StoreInterface[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(StoreRepositoryInterface $subject, $result)
    {

        foreach ($result as $store) { /** @var StoreInterface $store */
            /** @var StoreExtensionInterface $extensionAttributes */
            $extensionAttributes = $store->getExtensionAttributes();
            if (!$extensionAttributes) {
                $extensionAttributes = $this->storeExtensionFactory->create();
            }
            $extensionAttributes->setIsActive($store->getIsActive());
            $store->setExtensionAttributes($extensionAttributes);
        }
        return $result;
    }
}
