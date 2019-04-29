<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model\Autocomplete;

use Deity\CatalogApi\Model\ProductUrlPathProviderInterface;
use Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface;
use Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterfaceFactory;
use Deity\CatalogSearchApi\Model\Autocomplete\DataProviderInterface;
use Deity\CatalogSearchApi\Model\QueryCollectionServiceInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Layer\Search;
use Magento\Catalog\Model\Product\Visibility;

/**
 * Class ProductProvider
 *
 * @package Deity\CatalogSearch\Model\Autocomplete
 */
class ProductProvider implements DataProviderInterface
{

    public const AUTOCOMPLETE_TYPE_PRODUCT = 'product';

    /**
     * @var AutocompleteItemInterfaceFactory
     */
    private $itemFactory;

    /**
     * @var ProductUrlPathProviderInterface
     */
    private $urlPathProvider;
    /**
     * @var Search
     */
    private $searchLayer;

    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var Visibility
     */
    private $productVisibility;

    /**
     * @var QueryCollectionServiceInterface
     */
    private $queryCollectionService;

    /**
     * ProductProvider constructor.
     * @param AutocompleteItemInterfaceFactory $itemFactory
     * @param ProductUrlPathProviderInterface $urlPathProvider
     * @param Resolver $layerResolver
     * @param Visibility $productVisibility
     * @param QueryCollectionServiceInterface $queryCollectionService
     */
    public function __construct(
        AutocompleteItemInterfaceFactory $itemFactory,
        ProductUrlPathProviderInterface $urlPathProvider,
        Resolver $layerResolver,
        Visibility $productVisibility,
        QueryCollectionServiceInterface $queryCollectionService
    ) {
        $this->urlPathProvider = $urlPathProvider;
        $layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
        $this->itemFactory = $itemFactory;
        $this->searchLayer = $layerResolver->get();
        $this->layerResolver = $layerResolver;
        $this->productVisibility = $productVisibility;
        $this->queryCollectionService = $queryCollectionService;
    }

    /**
     * Provide autocomplete data
     *
     * @param string $query
     * @return AutocompleteItemInterface[]
     */
    public function getAutocompleteItemsForQuery(string $query): array
    {
        $result = [];
        $layer = $this->layerResolver->get();
        $collection = $layer->getProductCollection();

        $collection
            ->addAttributeToSelect(['name'])
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->setVisibility($this->productVisibility->getVisibleInSearchIds());

        $this->queryCollectionService->apply($collection, $query);

        foreach ($collection as $product) {
            $result[] = $this->itemFactory->create([
                AutocompleteItemInterface::TITLE => $product->getName(),
                AutocompleteItemInterface::URL_PATH => $this->urlPathProvider->getProductUrlPath($product),
                AutocompleteItemInterface::TYPE => self::AUTOCOMPLETE_TYPE_PRODUCT
            ]);
        }

        return $result;
    }
}
