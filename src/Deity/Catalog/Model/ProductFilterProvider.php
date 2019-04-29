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
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InitException;

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
     * @var string[][]
     */
    private $filterValues = [];

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
    public function getFilterList(Layer $layer, ?SearchCriteriaInterface $searchCriteria): array
    {

        $this->presetFilterValues($searchCriteria);
        
        /** @var AbstractFilter[] $magentoFilters */
        $magentoFilters = $this->filterList->getFilters($layer);
        $resultFilters = [];
        foreach ($magentoFilters as $magentoFilter) {
            if (!$magentoFilter->getItemsCount() && !$this->isFilterSelected($magentoFilter)) {
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
                        FilterOptionInterface::LABEL => (string)$magentoOption->getData('label'),
                        FilterOptionInterface::VALUE => $magentoOption->getValueString(),
                        FilterOptionInterface::COUNT => (int)$magentoOption->getData('count'),
                        FilterOptionInterface::IS_SELECTED =>
                            $this->getIsSelectedFlagForFilterOption(
                                $magentoFilter,
                                (string)$magentoOption->getValueString()
                            )
                    ]
                );
                $filterObject->addOption($filterOption);
            }

            $resultFilters[] = $filterObject;
        }
        return $resultFilters;
    }

    /**
     * Check if filter option is selected
     *
     * @param AbstractFilter $magentoFilter
     * @param string $getValueString
     * @return bool
     */
    private function getIsSelectedFlagForFilterOption(AbstractFilter $magentoFilter, string $getValueString): bool
    {
        if (!$this->isFilterSelected($magentoFilter)) {
            return false;
        }

        if (in_array($getValueString, $this->filterValues[$magentoFilter->getRequestVar()])) {
            return true;
        }

        return false;
    }

    /**
     * Check if filter values are selected
     *
     * @param AbstractFilter $magentoFilter
     * @return bool
     */
    private function isFilterSelected(AbstractFilter $magentoFilter): bool
    {
        return isset($this->filterValues[$magentoFilter->getRequestVar()]);
    }

    /**
     * Parse Filter Selected values
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return $this
     */
    private function presetFilterValues(?SearchCriteriaInterface $searchCriteria)
    {
        if ($searchCriteria === null) {
            return $this;
        }

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $this->filterValues[$filter->getField()][] = $filter->getValue();
            }
        }

        return $this;
    }
}
