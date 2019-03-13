<?php
declare(strict_types=1);

namespace Deity\QuoteApi\Test\Api;

use Deity\SalesApi\Api\OrderIdMaskRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GuestCartManagementTest
 *
 * @package Deity\QuoteApi\Test\Api
 */
class GuestCartManagementTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    const RESOURCE_PATH = '/V1/guest-carts/:cartId/place-order';

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
     * @magentoApiDataFixture ../../../../app/code/Deity/QuoteApi/Test/_files/guest_quote_with_check_payment.php
     */
    public function testPlaceOrderWithoutExtraPaymentInfo()
    {
        $this->_markTestAsRestOnly();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':cartId', $this->getMaskedIdFromQuoteFixture(), self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT
            ],
        ];

        $orderResponseObject = $this->_webApiCall($serviceInfo, []);
        $this->assertArrayHasKey('order_id', $orderResponseObject, 'response expected to have order_id field');
        $this->assertArrayHasKey(
            'order_real_id',
            $orderResponseObject,
            'response expected to have real_order_id field'
        );
        $orderId = $orderResponseObject['order_id'];
        $orderRealId = $orderResponseObject['order_real_id'];

        /** @var OrderIdMaskRepositoryInterface $orderIdMaskRepository */
        $orderIdMaskRepository = $this->objectManager->create(
            \Deity\SalesApi\Api\OrderIdMaskRepositoryInterface::class
        );

        $orderIdMask = $orderIdMaskRepository->getByMaskedOrderId($orderId);
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class)->load($orderIdMask->getOrderId());
        $items = $order->getAllItems();
        $this->assertCount(1, $items, 'order should have exactly one item');
        $this->assertEquals($orderRealId, $order->getIncrementId(), 'Order increment_id should match');
        $this->assertEquals('Simple Product', $items[0]->getName(), 'product name should match');
    }

    /**
     * Get masked_id from quote fixture
     *
     * @return string
     * @throws NoSuchEntityException
     */
    private function getMaskedIdFromQuoteFixture(): string
    {
        /** @var CartRepositoryInterface $quoteRepository */
        $quoteRepository = $this->objectManager->create(
            CartRepositoryInterface::class
        );
        /** @var SearchCriteriaBuilderFactory $searchCriteriaBuilder */
        $searchCriteriaBuilderFactory = $this->objectManager->create(
            SearchCriteriaBuilderFactory::class
        );
        /** @var SearchCriteria $searchCriteria */
        $searchCriteria = $searchCriteriaBuilderFactory->create()
            ->addFilter('customer_email', 'aaa@aaa.com', 'eq')
            ->create();
        /** @var CartSearchResultsInterface $searchResponse */
        $searchResult = $quoteRepository->getList($searchCriteria);
        $quotes = $searchResult->getItems();
        $quote = array_pop($quotes);
        $testQuoteId = (int)$quote->getId();

        /** @var QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedIdConverter */
        $quoteIdToMaskedIdConverter = $this->objectManager->create(
            QuoteIdToMaskedQuoteIdInterface::class
        );
        return $quoteIdToMaskedIdConverter->execute($testQuoteId);
    }
}
