<?php
declare(strict_types=1);

namespace Deity\Store\Test\Api;

use Magento\Integration\Model\AdminTokenServiceTest;
use Magento\Store\Api\StoreConfigManagerTest as MagentoStoreConfigManagerTest;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class StoreConfigManagerTest
 * @package Deity\Store\Test\Api
 */
class StoreConfigManagerTest extends WebapiAbstract
{
    public function testGetStoreConfigs()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => MagentoStoreConfigManagerTest::RESOURCE_PATH,
                'httpMethod'  => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
                ]
        ];

        $storeViews = $this->_webApiCall($serviceInfo, []);
        $this->assertTrue(isset($storeViews[0]['extension_attributes']), 'Store view should have extension attributes');
        $expectedExtensionKeys = [
            'optional_post_codes',
            'min_password_length',
            'min_password_char_class',
            'api_version'
        ];
        $this->assertEquals(
            $expectedExtensionKeys,
            array_intersect(array_keys($storeViews[0]['extension_attributes']), $expectedExtensionKeys),
            'Store view should contain new extension attributes'
        );
    }
}
