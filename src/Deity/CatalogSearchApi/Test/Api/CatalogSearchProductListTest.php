<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CatalogSearchProductListTest
 *
 * @package Deity\CatalogSearchApi\Test\Api
 */
class CatalogSearchProductListTest extends WebapiAbstract
{

    private const RESOURCE_PATH = '/V1/falcon/catalog-search';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/categories_with_children.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testGetListRequestSimpleQuery()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [],
                'current_page' => 1,
                'page_size' => 10
            ],
            'query' => 'simple'
        ];
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two products are expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/categories_with_filters.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testGetListWithFilters()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [],
                'current_page' => 1,
                'page_size' => 10
            ],
            'query' => 'simple'
        ];
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);
        $this->assertNotEmpty($response['filters'], "Filter data is expected");

        $filterableOption = array_filter(
            $response['filters'],
            function ($item) {
                if ($item['code'] == 'filterable_attribute') {
                    return true;
                }
                return false;
            }
        );
        $filterableOption = array_pop($filterableOption);

        $this->assertEquals(2, count($filterableOption['options']), 'Two filter option is expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/categories_with_children.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testGetListWithPageSizeParam()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [],
                'current_page' => 1,
                'page_size' => 1
            ],
            'query' => 'simple'
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two product is expected');
        $this->assertEquals(1, count($response['items']), 'One product is expected in response');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/categories_with_children.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testGetListWithCurrentPageParam()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [],
                'current_page' => 2,
                'page_size' => 1
            ],
            'query' => 'simple'
        ];

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two product is expected');

        $this->assertEquals(1, count($response['items']), 'One product is expected in response');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/categories_with_filters.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testGetListFilterReturnFields()
    {
        /** @var $attribute \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
        $attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\ResourceModel\Eav\Attribute::class
        );
        $attribute->loadByCode('catalog_product', 'filterable_attribute');
        /** @var \Magento\Eav\Api\Data\AttributeOptionInterface[]  $options */
        $options = $attribute->getOptions();
        /** @var \Magento\Eav\Api\Data\AttributeOptionInterface $testOption */
        //skip 0 one, that's default
        /** @var \Magento\Eav\Model\Entity\Attribute\Option $testOption */
        $testOption = $options[1];
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [
                    [
                        'filters' => [
                            [
                                'field' => 'filterable_attribute',
                                'value' => $testOption->getValue(),
                                'condition_type' => 'eq',
                            ],
                        ],
                    ],
                ],
                'current_page' => 1,
                'page_size' => 2,
            ],
            'query' => 'simple'
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);
        $this->assertEquals(1, $response['total_count'], 'Only one product is expected');
    }
}
