<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Deity\CatalogApi\Api\Data\FilterInterfaceFactory;
use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Deity\CatalogApi\Api\Data\FilterOptionInterfaceFactory;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item;

/**
 * Class ProductFilterProvider
 *
 * @package Deity\Catalog\Model
 */
class ProductFilterProvider implements \Deity\CatalogApi\Api\ProductFilterProviderInterface
{

    /**
     * @var Layer\FilterList
     */
    private $filterList;

    /**
     * @var FilterInterfaceFactory;
     */
    private $filterFactory;

    /**
     * @var FilterOptionInterfaceFactory
     */
    private $filterOptionFactory;

    /**
     * ProductFilterProvider constructor.
     * @param Layer\FilterList $filterList
     * @param FilterInterfaceFactory $filterFactory
     * @param FilterOptionInterfaceFactory $filterOptionFactory
     */
    public function __construct(
        Layer\FilterList $filterList,
        FilterInterfaceFactory $filterFactory,
        FilterOptionInterfaceFactory $filterOptionFactory
    ) {
        $this->filterList = $filterList;
        $this->filterOptionFactory = $filterOptionFactory;
        $this->filterFactory = $filterFactory;
    }

    /**
     * @inheritdoc
     */
    public function getFilterList(Layer $layer): array
    {
        if (!$layer->getCurrentCategory()->getIsAnchor()) {
            //if category is not marked is_anchor, do not return filter data
            return [];
        }
        
        /** @var AbstractFilter[] $magentoFilters */
        $magentoFilters = $this->filterList->getFilters($layer);
        $resultFilters = [];
        foreach ($magentoFilters as $magentoFilter) {
            if (!$magentoFilter->getItemsCount()) {
                continue;
            }
            $filterInitData = [];
            $filterInitData['label'] = (string)$magentoFilter->getName();
            if ($magentoFilter->getRequestVar() == 'cat') {
                $filterInitData['code'] = $magentoFilter->getRequestVar();
                $filterInitData['type'] = 'int';
                $filterInitData['attributeId'] = 0;
            } else {
                $filterInitData['code'] = $magentoFilter->getAttributeModel()->getAttributeCode();
                $filterInitData['type'] = $magentoFilter->getAttributeModel()->getBackendType();
                $filterInitData['attributeId'] = (int)$magentoFilter->getAttributeModel()->getAttributeId();
            }
            /** @var FilterInterface $filterObject */
            $filterObject = $this->filterFactory->create($filterInitData);
            $magentoOptions = $magentoFilter->getItems();

            /** @var Item $magentoOption */
            foreach ($magentoOptions as $magentoOption) {
                /** @var FilterOptionInterface $filterOption */
                $filterOption =$this->filterOptionFactory->create(
                    [
                        'label' => (string)$magentoOption->getData('label'),
                        'value' => $magentoOption->getValueString(),
                        'count' => (int)$magentoOption->getData('count')
                    ]
                );
                $filterObject->addOption($filterOption);
            }

            $resultFilters[] = $filterObject;
        }
        return $resultFilters;
    }
}
