<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class SearchAutocompleteTest
 *
 * @package Deity\CatalogSearchApi\Test\Api
 */
class SearchAutocompleteTest extends WebapiAbstract
{
    private const AUTOCOMPLETE_REST_PATH = '/V1/falcon/catalog-search/autocomplete?q=#query';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/searchable_products.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testProductAutocomplete()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace('#query', 'simple', self::AUTOCOMPLETE_REST_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(3, count($response), 'Three items expected to be returned');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/searchable_products.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testProductReturnFields()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace('#query', 'shoe', self::AUTOCOMPLETE_REST_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);
        $this->assertEquals(1, count($response), 'One item expected to be returned');

        $productInfo = $response[0];

        $this->assertEquals('Simple Shoe', $productInfo['title']);
        $this->assertEquals('simple-shoe.html', $productInfo['url_path']);
        $this->assertEquals('product', $productInfo['type']);
    }
}
