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
    const RESOURCE_PATH = '/V1/categories/:categoryId/products';

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
}
