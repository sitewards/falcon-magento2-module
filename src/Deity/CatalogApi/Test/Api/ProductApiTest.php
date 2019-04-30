<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Test\Api;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Deity\MagentoApi\Helper\Product;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class ProductApiTest
 *
 * @package Deity\CatalogApi\Test\Api
 */
class ProductApiTest extends WebapiAbstract
{
    const PRODUCT_API_ENDPOINT = '/V1/falcon/products/:sku';

    /**
     * @param string $productSku
     * @return array
     */
    private function getProductData($productSku)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':sku', $productSku, self::PRODUCT_API_ENDPOINT),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        return $this->_webApiCall($serviceInfo);
    }

    /**
     * @magentoApiDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testProductApiExist()
    {
        try {
            $resposeData = $this->getProductData('simple');
        } catch (\Exception $e) {
            $this->fail("Product data response expected");
        }

        /** @var array $resposeData */
        $this->assertEquals(1, $resposeData[ProductDetailInterface::ID_FIELD_KEY], 'Product id should match');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/product_with_complete_price_data.php
     */
    public function testProductPriceInfo()
    {
        $productData = $this->getProductData('simple');

        $this->assertArrayHasKey(
            ProductDetailInterface::PRICE_FIELD_KEY,
            $productData,
            "product should have price data"
        );

        $priceData = $productData[ProductDetailInterface::PRICE_FIELD_KEY];

        $this->assertEquals(
            10,
            $priceData[ProductPriceInterface::REGULAR_PRICE],
            "regular price should match"
        );

        $this->assertEquals(
            9.99,
            $priceData[ProductPriceInterface::SPECIAL_PRICE],
            "special price should match"
        );

        $this->assertEquals(
            5,
            $priceData[ProductPriceInterface::MIN_TIER_PRICE],
            "minimal tier price should match"
        );
    }

    /**
     * @magentoApiDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testProductMainFields()
    {
        $productData = $this->getProductData('simple');

        $this->assertEquals('simple', $productData[ProductDetailInterface::SKU_FIELD_KEY], 'Product sku should match');
        $this->assertEquals(
            'Simple Product',
            $productData[ProductDetailInterface::NAME_FIELD_KEY],
            'Product name should match'
        );

        $this->assertEquals(
            'simple',
            $productData[ProductDetailInterface::TYPE_ID_FIELD_KEY],
            'Product type id should match'
        );

        $this->assertEquals(
            'simple-product.html',
            $productData[ProductDetailInterface::URL_PATH_FIELD_KEY],
            'Product url path should match'
        );

        $this->assertEquals(
            1,
            $productData[ProductDetailInterface::IS_SALABLE_FIELD_KEY],
            'Product is_salable flag should match'
        );

        $this->assertEquals(
            5,
            count($productData[ProductDetailInterface::TIER_PRICES_FIELD_KEY]),
            'Product should contain complete set of tier price information'
        );
    }
}
