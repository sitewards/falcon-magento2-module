<?php
declare(strict_types=1);

namespace Deity\Customer\Model;

use Deity\Customer\Model\Security\CustomerContext;
use Deity\CustomerApi\Api\NewsletterManagerInterface;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;

/**
 * Class NewsletterManager
 * @package Deity\Customer\Model
 */
class NewsletterManager implements NewsletterManagerInterface
{
    /** @var CustomerContext */
    private $customerContext;

    /** @var SubscriberFactory */
    private $subscriberFactory;

    /**
     * @param CustomerContext $customerContext
     * @param SubscriberFactory $subscriberFactory
     */
    public function __construct(
        CustomerContext $customerContext,
        SubscriberFactory $subscriberFactory
    ) {
        $this->customerContext = $customerContext;
        $this->subscriberFactory = $subscriberFactory;
    }

    /**
     * @param int $customerId
     * @return bool
     * @throws AuthorizationException
     */
    public function subscribeCustomer($customerId)
    {
        $this->customerContext->checkCustomerContext($customerId);

        /** @var Subscriber $subscriber */
        $subscriber = $this->subscriberFactory->create();
        $subscriber->subscribeCustomerById($customerId);

        return $subscriber->isSubscribed();
    }

    /**
     * @param int $customerId
     * @return bool
     * @throws AuthorizationException
     */
    public function unsubscribeCustomer($customerId)
    {
        $this->customerContext->checkCustomerContext($customerId);

        /** @var Subscriber $subscriber */
        $subscriber = $this->subscriberFactory->create();
        $subscriber->unsubscribeCustomerById($customerId);

        return !$subscriber->isSubscribed();
    }
}
