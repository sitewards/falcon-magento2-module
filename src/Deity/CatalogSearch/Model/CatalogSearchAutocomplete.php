<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model;

use Deity\CatalogSearchApi\Api\SearchAutocompleteInterface;
use Deity\CatalogSearchApi\Model\Autocomplete\DataProviderInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class CatalogSearchAutocomplete
 *
 * @package Deity\CatalogSearch\Model
 */
class CatalogSearchAutocomplete implements SearchAutocompleteInterface
{
    /**
     * @var \Deity\CatalogSearchApi\Model\Autocomplete\DataProviderInterface[]
     */
    private $dataProviders;

    /**
     * @param array $dataProviders
     * @throws LocalizedException
     */
    public function __construct(
        array $dataProviders
    ) {
        foreach ($dataProviders as $dataProvider) {
            if (! $dataProvider instanceof DataProviderInterface) {
                throw new LocalizedException(
                    __('Data Provider must implement DataProviderInterface.')
                );
            }
        }
        $this->dataProviders = $dataProviders;
        ksort($this->dataProviders);
    }

    /**
     * Main search method
     *
     * @param string $q
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface[]
     */
    public function search(string $q): array
    {
        $data = [];
        foreach ($this->dataProviders as $dataProvider) {
            $data = array_merge($data, $dataProvider->getAutocompleteItemsForQuery($q));
        }

        return $data;
    }
}
