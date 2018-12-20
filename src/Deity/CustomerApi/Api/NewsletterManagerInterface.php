<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Api;

/**
 * Interface NewsletterManagerInterface
 * @package Deity\CustomerApi\Api
 */
interface NewsletterManagerInterface
{
    /**
     * Subscribe customer to newsletter
     *
     * @param int $customerId
     * @return bool
     */
    public function subscribeCustomer($customerId);

    /**
     * Unsubscribe customer from newsletter
     *
     * @param int $customerId
     * @return bool
     */
    public function unsubscribeCustomer($customerId);
}
