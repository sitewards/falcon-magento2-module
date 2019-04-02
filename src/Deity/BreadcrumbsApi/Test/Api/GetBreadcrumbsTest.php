<?php
declare(strict_types=1);

namespace Deity\BreadcrumbsApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GetBreadcrumbsTest
 * @package Deity\BreadcrumbsApi\Test\Api
 */
class GetBreadcrumbsTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    private const RESOURCE_PATH = '/V1/falcon/breadcrumbs';

    /**
     * @param $existingUrl
     * @return array
     */
    public function getBreadcrumbs(string $existingUrl): array
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
     * @magentoApiDataFixture ../../../../app/code/Deity/BreadcrumbsApi/Test/_files/categories_with_children.php
     */
    public function testProductBreadcrumb()
    {
        $breadcrumbs = $this->getBreadcrumbs('winter.html');
        $this->assertEmpty($breadcrumbs, 'no actual breadcrumbs expected');
        $breadcrumbs = $this->getBreadcrumbs('winter/canada-sweater.html');
        $this->assertEquals(1, count($breadcrumbs), 'one breadcrumb expected');
        $breadcrumbData = array_pop($breadcrumbs);
        $this->assertEquals('winter.html', $breadcrumbData['url_path']);
        $this->assertEquals('Category 1', $breadcrumbData['name']);

        $breadcrumbs = $this->getBreadcrumbs('winter/shoes.html');
        $this->assertEquals(1, count($breadcrumbs), 'one breadcrumb expected');
        $breadcrumbData = array_pop($breadcrumbs);
        $this->assertEquals('winter.html', $breadcrumbData['url_path']);
        $this->assertEquals('Category 1', $breadcrumbData['name']);
        $breadcrumbs = $this->getBreadcrumbs('winter/shoes/alaska-shoe.html');
        $this->assertEquals(2, count($breadcrumbs), 'two breadcrumb expected');
        $breadcrumbData = $breadcrumbs[0];
        $this->assertEquals('winter.html', $breadcrumbData['url_path']);
        $this->assertEquals('Category 1', $breadcrumbData['name']);
        $breadcrumbData = $breadcrumbs[1];
        $this->assertEquals('winter/shoes.html', $breadcrumbData['url_path']);
        $this->assertEquals('Category 1.1', $breadcrumbData['name']);
    }
}
