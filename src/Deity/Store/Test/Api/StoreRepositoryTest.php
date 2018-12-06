<?php
declare(strict_types=1);

namespace Deity\Store\Test\Api;

use Magento\Integration\Model\AdminTokenServiceTest;
use Magento\Store\Api\StoreRepositoryTest as MagentoStoreRepositoryTest;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class StoreRepositoryTest
 * @package Deity\Store\Test\Api
 */
class StoreRepositoryTest extends WebapiAbstract
{

    public function testGetList()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => MagentoStoreRepositoryTest::RESOURCE_PATH,
                'httpMethod'  => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];

        $storeViews = $this->_webApiCall($serviceInfo, []);
        $this->assertTrue(isset($storeViews[0]['extension_attributes']), 'Store view should have extension attributes');
        $this->assertTrue(isset($storeViews[0]['extension_attributes']['is_active']), 'Store view should be active');
    }
}
