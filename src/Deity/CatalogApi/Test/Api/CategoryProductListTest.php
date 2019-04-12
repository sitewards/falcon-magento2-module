<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CategoryProductListTest
 * @package Deity\CatalogApi\Test\Api
 */
class CategoryProductListTest extends WebapiAbstract
{
    private const RESOURCE_PATH = '/V1/falcon/categories/:categoryId/products';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testGetListRequestNoParameters()
    {
        $childCategoryId = 4;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $childCategoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, $response['total_count'], 'One product is expected');

        $parentCategoryId = 3;

        $serviceInfo['rest']['resourcePath'] = str_replace(':categoryId', $parentCategoryId, self::RESOURCE_PATH);

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two products are expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testEmptyFiltersForCategoriesWithouthAnchor()
    {
        $childCategoryId = 4;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $childCategoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEmpty(
            $response['filters'],
            'Filter array should be empty'
        );
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testGetListUrlPath()
    {
        $childCategoryId = 4;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $childCategoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $productData = array_pop($response['items']);
        $this->assertEquals(
            'first/child/simple-two.html',
            $productData['url_path'],
            'Product should have url within category context'
        );
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    public function testGetListNoParametersWithFilters()
    {
        $categoryId = 4;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEmpty($response['filters'], "Filter data is not expected");

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH),
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
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testGetListWithPageSizeParam()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [],
                'current_page' => 1,
                'page_size' => 1
            ],
        ];

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two product is expected');

        $this->assertEquals(1, count($response['items']), 'One product is expected in response');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testGetListWithCurrentPageParam()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [],
                'current_page' => 2,
                'page_size' => 1
            ],
        ];

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two product is expected');

        $this->assertEquals(1, count($response['items']), 'One product is expected in response');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
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
        ];

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);
        $this->assertEquals(1, $response['total_count'], 'Only one product is expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_three_products.php
     */
    public function testPriceFilter()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [
                    [
                        'filters' => [
                            [
                                'field' => 'price',
                                'value' => '-9',
                                'condition_type' => 'eq',
                            ],
                        ],
                    ],
                ],
                'current_page' => 1,
                'page_size' => 2,
            ],
        ];

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(0, $response['total_count'], 'No products expected');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '-10';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, $response['total_count'], 'One product expected');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '-30';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two products expected');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '20-30';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, $response['total_count'], 'One product expected');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '30-';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two products expected with price over 30');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '60-';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, $response['total_count'], 'One product expected with price over 60');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '70-';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(0, $response['total_count'], 'No products expected');

        $searchCriteria['searchCriteria']['filter_groups'][0]['filters'][0]['value'] = '10-60';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(3, $response['total_count'], 'Three product expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    public function testSelectedFilterFlagIsOn()
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
        ];

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH) .
                    '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, count($response['filters']), 'At least one facet expected');
        foreach ($response['filters'] as $filter) {
            if ($filter['code'] == 'filterable_attribute') {
                foreach ($filter['options'] as $option) {
                    if ($option['value'] === $testOption->getValue()) {
                        $this->assertEquals(true, $option['is_selected'], 'Filter option should be marked selected');
                    }
                }
            }
        }
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    public function testSelectedFilterFlagIsOff()
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
        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        // Category filter + price filter + custom attribute filter
        $this->assertEquals(3, count($response['filters']), 'Three filter facets expected');
        foreach ($response['filters'] as $filter) {
            if ($filter['code'] == 'filterable_attribute') {
                foreach ($filter['options'] as $option) {
                    if ($option['value'] === $testOption->getValue()) {
                        $this->assertEquals(
                            false,
                            $option['is_selected'],
                            'Filter option should NOT be marked selected'
                        );
                    }
                }
            }
        }
    }
}
