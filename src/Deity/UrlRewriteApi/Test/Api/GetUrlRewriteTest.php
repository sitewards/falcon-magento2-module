<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GetUrlRewriteTest
 * @package Deity\UrlRewriteApi\Test\Api
 */
class GetUrlRewriteTest extends WebapiAbstract
{

    /**
     * Service constants
     */
    private const RESOURCE_PATH = '/V1/falcon/urls/:url';

    /**
     * @param $existingUrl
     * @return array
     */
    public function getUrlRewriteInfo(string $existingUrl): array
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':url', $existingUrl, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $item = $this->_webApiCall($serviceInfo, []);
        return $item;
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/UrlRewriteApi/Test/_files/url_rewrite.php
     */
    public function testExecute()
    {
        $item = $this->getUrlRewriteInfo('page-a');
        $this->assertEquals('CMS_PAGE', $item['entity_type'], "Item was retrieved successfully");
        
        $item = $this->getUrlRewriteInfo('category-one');
        $this->assertEquals('CATEGORY', $item['entity_type'], "Item was retrieved successfully");
        $this->assertContains('category-one', $item['canonical_url'], 'Request path appears in canonical URL.');
    }

    /**
     * @magentoApiDataFixture Magento/Catalog/_files/products.php
     */
    public function testProductSkuReturn()
    {
        $item = $this->getUrlRewriteInfo('simple-product.html');

        $this->assertEquals('PRODUCT', $item['entity_type'], "Item was retrieved successfully");
        $this->assertEquals('simple', $item['entity_id'], "Item entity id was successfully converted");
        $this->assertContains('simple-product.html', $item['canonical_url'], 'Request path appears in canonical URL.');
    }
}
