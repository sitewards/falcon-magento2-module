<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

class GetUrlRewriteTest extends WebapiAbstract
{

    /**
     * Service constants
     */
    const RESOURCE_PATH = '/V1/url';

    /**
     * @param $existingUrl
     * @return array
     */
    public function getUrlRewriteInfo(string $existingUrl): array
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . "?url=" . $existingUrl,
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
    }

    /**
     * @magentoApiDataFixture Magento/Catalog/_files/products.php
     */
    public function testProductSkuReturn()
    {
        $item = $this->getUrlRewriteInfo('simple-product.html');

        $this->assertEquals('PRODUCT', $item['entity_type'], "Item was retrieved successfully");
        $this->assertEquals('simple', $item['entity_id'], "Item entity id was successfully converted");
    }
}
