<?php
declare(strict_types=1);

namespace Deity\Catalog\Test\Integration;

use Deity\CatalogApi\Api\Data\ProductInterface;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductConvertTest
 * @package Deity\Catalog\Test\Integration
 */
class ProductConvertTest extends TestCase
{
    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->create(ProductRepositoryInterface::class);
        $this->categoryRepository = $this->objectManager->create(CategoryRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_with_image.php
     */
    public function testConvertWithoutSpecialPrice()
    {
        /** @var ProductConvertInterface $productConverter */
        $productConverter = $this->objectManager->create(ProductConvertInterface::class);
        $magentoProductObject = $this->productRepository->get('simple');

        /** @var ProductInterface $convertedProduct */
        $convertedProduct = $productConverter->convert($magentoProductObject);
        $this->assertEquals('simple', $convertedProduct->getSku(), 'return product should have original product sku');
        $this->assertEquals(
            'Simple Product',
            $convertedProduct->getName(),
            'return product should have original product name'
        );
        $this->assertEquals(1, $convertedProduct->getIsSalable(), 'return product should have salable indicator');
        $this->assertEquals(
            'simple-product.html',
            $convertedProduct->getUrlPath(),
            'return product should have url path'
        );
        $this->assertNotEmpty($convertedProduct->getImage(), 'result image should provide at least a placeholder');

        $productPriceData = $convertedProduct->getPrice();

        $this->assertEquals(10, $productPriceData->getRegularPrice(), 'original product price should match');
        $this->assertEquals(
            5,
            $productPriceData->getMinTierPrice(),
            'tier price should be set to minimal available value'
        );
        $this->assertEquals(
            null,
            $productPriceData->getSpecialPrice(),
            'if product has no special price, should be set to null'
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_special_price.php
     */
    public function testConvertWithSpecialPrice()
    {
        /** @var ProductConvertInterface $productConverter */
        $productConverter = $this->objectManager->create(ProductConvertInterface::class);
        $magentoProductObject = $this->productRepository->get('simple');

        /** @var ProductInterface $convertedProduct */
        $convertedProduct = $productConverter->convert($magentoProductObject);

        $productPriceData = $convertedProduct->getPrice();

        $this->assertEquals(10, $productPriceData->getRegularPrice(), 'original product price should match');
        $this->assertEquals(null, $productPriceData->getMinTierPrice(), 'if no tier price set, should be null');
        $this->assertEquals(
            5.99,
            $productPriceData->getSpecialPrice(),
            'exact value of special price should be provided'
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/category_product.php
     */
    public function testConvertProductCategory()
    {
        /** @var ProductConvertInterface $productConverter */
        $productConverter = $this->objectManager->create(ProductConvertInterface::class);
        $magentoProductObject = $this->productRepository->get('simple333');

        /** @var \Magento\Framework\Registry $registry */
        $registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->get(\Magento\Framework\Registry::class);
        /** @var Category $category */
        $category = $this->categoryRepository->get(333);
        $registry->register('current_category', $category);

        /** @var ProductInterface $convertedProduct */
        $convertedProduct = $productConverter->convert($magentoProductObject);

        $this->assertEquals(
            'category-1/simple-product-three.html',
            $convertedProduct->getUrlPath(),
            'url path should be within category context'
        );
    }
}
