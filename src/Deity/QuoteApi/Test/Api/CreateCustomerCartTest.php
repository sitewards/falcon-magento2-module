<?php
declare(strict_types=1);

namespace Deity\QuoteApi\Test\Api;

use Magento\Framework\App\Config;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CreateCustomerCartTest
 *
 * @package Deity\QuoteApi\Test\Api
 */
class CreateCustomerCartTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    private const GET_CUSTOMER_CART_PATH = '/V1/falcon/carts/mine';

    private const GET_GUEST_CART_PATH = '/V1/guest-carts';

    private const ADD_PRODUCT_TO_GUEST_CART_PATH = '/V1/guest-carts/:cartId/items';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     *  setup before every test run. Update app config
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $appConfig = $this->objectManager->get(Config::class);
        $appConfig->clean();
    }

    /**
     * clean up all quote objects created during testing
     */
    protected function tearDown()
    {
        /** @var \Magento\Quote\Model\ResourceModel\Quote\Collection $quoteCollection */
        $quoteCollection = $this->objectManager->create(\Magento\Quote\Model\ResourceModel\Quote\Collection::class);

        /** @var \Magento\Quote\Model\QuoteRepository $quoteRepository */
        $quoteRepository = $this->objectManager->create(\Magento\Quote\Model\QuoteRepository::class);

        foreach ($quoteCollection as $quote) {
            $quoteRepository->delete($quote);
        }
    }

    /**
     * @magentoApiDataFixture Magento/Customer/_files/customer.php
     */
    public function testExecute()
    {
        $this->_markTestAsRestOnly();

        // get customer ID token
        /** @var \Magento\Integration\Api\CustomerTokenServiceInterface $customerTokenService */
        $customerTokenService = $this->objectManager->create(
            \Magento\Integration\Api\CustomerTokenServiceInterface::class
        );
        $token = $customerTokenService->createCustomerAccessToken('customer@example.com', 'password');

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::GET_CUSTOMER_CART_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
                'token' => $token
            ],
        ];

        $cartId = $this->_webApiCall($serviceInfo, []);

        $this->assertNotEmpty($cartId, 'Cart id should not be empty');

        /** @var \Magento\Quote\Model\QuoteRepository $quoteRepository */
        $quoteRepository = $this->objectManager->create(\Magento\Quote\Model\QuoteRepository::class);
        /** @var Quote $quote */
        $quote = $quoteRepository->getActive($cartId);

        $quoteAddress = $quote->getShippingAddress();

        $this->assertNotEmpty($quoteAddress->getId(), 'Quote shipping address should be already created');
    }

    /**
     * @magentoApiDataFixture Magento/Customer/_files/customer.php
     * @magentoApiDataFixture Magento/Catalog/_files/products.php
     */
    public function testCartMergeAfterLogin()
    {

        $this->_markTestAsRestOnly();

        $testProductSKU = 'simple';
        $testProductQty = 12;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::GET_GUEST_CART_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST
            ],
        ];

        $guestCartId = $this->_webApiCall($serviceInfo, []);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':cartId', $guestCartId, self::ADD_PRODUCT_TO_GUEST_CART_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST
            ],
        ];

        $addProductRequestData = [
            'cartItem' => [
                'sku' => $testProductSKU,
                'quote_id' => $guestCartId,
                'qty' => $testProductQty
            ]
        ];

        $cartItemInfo = $this->_webApiCall($serviceInfo, $addProductRequestData);

        $this->assertArrayHasKey('item_id', $cartItemInfo, 'Response should be a valid cart item object');
        $this->assertNotEmpty($cartItemInfo['item_id'], 'cart item should have id');

        // get customer ID token
        /** @var \Magento\Integration\Api\CustomerTokenServiceInterface $customerTokenService */
        $customerTokenService = $this->objectManager->create(
            \Magento\Integration\Api\CustomerTokenServiceInterface::class
        );
        $token = $customerTokenService->createCustomerAccessToken('customer@example.com', 'password');

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::GET_CUSTOMER_CART_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
                'token' => $token
            ],
        ];

        $cartId = $this->_webApiCall($serviceInfo, ['masked_quote_id' => $guestCartId]);

        $this->assertNotEmpty($cartId, 'Cart id should not be empty');

        /** @var \Magento\Quote\Model\QuoteRepository $quoteRepository */
        $quoteRepository = $this->objectManager->create(\Magento\Quote\Model\QuoteRepository::class);
        /** @var Quote $quote */
        $quote = $quoteRepository->getActive($cartId);
        $itemsQty = $quoteAddress = $quote->getItemsQty();

        $this->assertNotEmpty($quote->getItems(), 'Cart items should not be empty');
        $this->assertEquals(
            $testProductQty,
            $itemsQty,
            "Customer should have exactly {$testProductQty} products after login"
        );
    }
}
