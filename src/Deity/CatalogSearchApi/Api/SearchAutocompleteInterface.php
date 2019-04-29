<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Api;

/**
 * Interface SearchAutocompleteInterface
 *
 * @package Deity\CatalogSearchApi\Api
 * @api
 */
interface SearchAutocompleteInterface
{
    /**
     * Main search method
     *
     * @param string $q
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface[]
     */
    public function search(string $q): array;
}
