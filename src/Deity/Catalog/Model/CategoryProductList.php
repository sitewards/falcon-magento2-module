<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\CategoryProductListInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterfaceFactory;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\ProductFilterProviderInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Registry;

/**
 * Class CategoryProductList
 *
 * @package Deity\Catalog\Model
 */
class CategoryProductList implements CategoryProductListInterface
{

    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private $productSearchResultFactory;
    
    /**
     * @var ProductConvertInterface
     */
    private $productConverter;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    private $catalogLayer;

    /**
     * @var Stock
     */
    private $stockHelper;

    /**
     * @var ProductFilterProviderInterface
     */
    private $filterProvider;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * CategoryProductList constructor.
     * @param ProductSearchResultsInterfaceFactory $productSearchResultFactory
     * @param ProductConvertInterface $convert
     * @param Stock $stockHelper
     * @param Resolver $layerResolver
     * @param ProductFilterProviderInterface $productFilterProvider
     * @param Registry $registry
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ProductSearchResultsInterfaceFactory $productSearchResultFactory,
        ProductConvertInterface $convert,
        Stock $stockHelper,
        Resolver $layerResolver,
        ProductFilterProviderInterface $productFilterProvider,
        Registry $registry,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->registry = $registry;
        $this->stockHelper = $stockHelper;
        $this->collectionProcessor = $collectionProcessor;
        $this->filterProvider = $productFilterProvider;
        $this->productConverter = $convert;
        $this->productSearchResultFactory = $productSearchResultFactory;
        $this->catalogLayer = $layerResolver->get();
    }

    /**
     * @inheritdoc
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): ProductSearchResultsInterface {
        
        $this->catalogLayer->setCurrentCategory($categoryId);

        $this->registry->register('current_category', $this->catalogLayer->getCurrentCategory());

        if ($searchCriteria !== null) {
            $this->collectionProcessor->process($searchCriteria, $this->getProductCollection());
        }

        $responseProducts = [];
        foreach ($this->getProductCollection() as $productObject) {
            /** @var $productObject Product */
            $responseProducts[] = $this->productConverter->convert($productObject);
        }
        /** @var ProductSearchResultsInterface $productSearchResult */
        $productSearchResult = $this->productSearchResultFactory->create();

        $filters = [];
        if ($this->catalogLayer->getCurrentCategory()->getIsAnchor()) {
            $filters = $this->filterProvider->getFilterList($this->catalogLayer, $searchCriteria);
        }

        $productSearchResult->setFilters($filters);

        $productSearchResult->setItems($responseProducts);

        $productSearchResult->setTotalCount($this->getProductCollection()->getSize());

        return $productSearchResult;
    }

    /**
     * Get product collection
     *
     * @return Collection
     */
    private function getProductCollection(): Collection
    {
        $collection = $this->catalogLayer->getProductCollection();
        $this->stockHelper->addIsInStockFilterToCollection($collection);
        return $collection;
    }
}
