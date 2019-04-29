<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model\Autocomplete;

use Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface;
use Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterfaceFactory as ItemFactory;
use Deity\CatalogSearchApi\Model\Autocomplete\DataProviderInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Search\Model\AutocompleteInterface;
use Magento\Search\Model\AutocompleteInterfaceFactory;

/**
 * Class SuggestionProvider
 *
 * @package Deity\CatalogSearch\Model\Autocomplete
 */
class SuggestionProvider implements DataProviderInterface
{
    public const AUTOCOMPLETE_TYPE_SUGGESTION = 'suggestion';

    /**
     * @var State
     */
    private $state;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Autocomplete result item factory
     *
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var AutocompleteInterfaceFactory
     */
    private $autocompleteFactory;

    /**
     * @param ItemFactory $itemFactory
     * @param State $state
     * @param Context $context
     * @param AutocompleteInterfaceFactory $autocompleteFactory
     */
    public function __construct(
        ItemFactory $itemFactory,
        State $state,
        Context $context,
        AutocompleteInterfaceFactory $autocompleteFactory
    ) {
        $this->autocompleteFactory = $autocompleteFactory;
        $this->state = $state;
        $this->request = $context->getRequest();
        $this->itemFactory = $itemFactory;
    }

    /**
     * Provide autocomplete data
     *
     * @param string $query
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface[]
     * @throws \Exception
     */
    public function getAutocompleteItemsForQuery(string $query): array
    {
        $result = [];
        if (!$this->request->getParam('q', false)) {
            return $result;
        }

        $magentoAutocompleteItems = $this->state->emulateAreaCode(
            'frontend',
            function (AutocompleteInterface $autocompleteObject) {
                $responseData = [];
                foreach ($autocompleteObject->getItems() as $resultItem) {
                    $responseData[] = $resultItem->toArray();
                }
                return $responseData;
            },
            [$this->autocompleteFactory->create()]
        );

        foreach ($magentoAutocompleteItems as $item) {
            $result[] = $this->itemFactory->create([
                AutocompleteItemInterface::TITLE => $item['title'],
                AutocompleteItemInterface::URL_PATH => '',
                AutocompleteItemInterface::TYPE => self::AUTOCOMPLETE_TYPE_SUGGESTION
            ]);
        }
        return $result;
    }
}
