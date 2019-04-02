<?php
declare(strict_types=1);

namespace Deity\MenuApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GetMenuTest
 * @package Deity\MenuApi\Test\Api
 */
class GetMenuTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    private const RESOURCE_PATH = '/V1/falcon/menus';

    /**
     * @return array
     */
    public function getMenu(): array
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        return $this->_webApiCall($serviceInfo, []);
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/MenuApi/Test/_files/category_tree_one_branch.php
     */
    public function testPlainTreeStructure()
    {
        $menuData = $this->getMenu();
        //testing categery level 1 data
        $this->assertEquals(1, count($menuData), "One menu item expected per level");
        $firstLevelData = array_pop($menuData);
        $this->assertEquals(3, $firstLevelData['id'], "Category id should match");
        $this->assertEquals("Level 1", $firstLevelData['name'], "Category name should match");
        $this->assertEquals("level-one.html", $firstLevelData['url_path'], "Category path should match");

        //testing categery level 2 data
        $this->assertEquals(1, count($firstLevelData['children']), "One menu item expected per level");
        $secondLevelCategory = array_pop($firstLevelData['children']);
        $this->assertEquals(4, $secondLevelCategory['id'], "Category id should match");
        $this->assertEquals("Level 2", $secondLevelCategory['name'], "Category name should match");
        $this->assertEquals("level-one/level-two.html", $secondLevelCategory['url_path'], "Category path should match");

        //testing categery level 3 data
        $this->assertEquals(1, count($secondLevelCategory['children']), "One menu item expected per level");
        $thirdLevelCategory = array_pop($secondLevelCategory['children']);
        $this->assertEquals(5, $thirdLevelCategory['id'], "Category id should match");
        $this->assertEquals("Level 3", $thirdLevelCategory['name'], "Category name should match");
        $this->assertEquals(
            "level-one/level-two/level-three.html",
            $thirdLevelCategory['url_path'],
            "Category path should match"
        );
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/MenuApi/Test/_files/category_tree.php
     */
    public function testIsActiveIncludeInMenu()
    {
        $menuData = $this->getMenu();

        $this->assertEquals(2, count($menuData), 'Two main categories expected');

        $categoryWithChildren = array_pop($menuData);

        $this->assertEquals(2, count($categoryWithChildren['children']), "Category should have 2 sub categories");

        $categoryWithoutChildren = array_pop($menuData);

        $this->assertEquals(0, count($categoryWithoutChildren['children']), "Category should have 0 child categories");
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/MenuApi/Test/_files/category_corrupt_data.php
     * @expectedException \Exception
     */
    public function testCategoryCorruptData()
    {
        $menuData = $this->getMenu();
    }

    public function testNoCategoryDataAvailable()
    {
        $menu = $this->getMenu();
        $this->assertEmpty($menu, 'No data should be returned');
    }
}
