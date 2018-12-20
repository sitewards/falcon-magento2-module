<?php
declare(strict_types=1);

namespace Deity\Customer\Model\Security;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\AuthorizationException;

/**
 * Class CustomerContext
 * @package Deity\Customer\Model\Security
 */
class CustomerContext
{
    /** @var UserContextInterface */
    private $userContext;

    /**
     * @param UserContextInterface $userContext
     */
    public function __construct(UserContextInterface $userContext)
    {
        $this->userContext = $userContext;
    }

    /**
     * Get current user id
     *
     * @return int
     */
    public function getCurrentCustomerId(): int
    {
        return (int)$this->userContext->getUserId();
    }

    /**
     * Check if current user context is for logged in customer
     *
     * @param int $customerId
     * @return bool
     * @throws AuthorizationException
     */
    public function checkCustomerContext($customerId = null): bool
    {
        if ($this->userContext->getUserType() !== UserContextInterface::USER_TYPE_CUSTOMER) {
            throw new AuthorizationException(__('This method is available only for customer tokens'));
        }

        if ($customerId && $this->getCurrentCustomerId() !== $customerId) {
            throw new AuthorizationException(__('You are not authorized to perform this action'));
        }

        return true;
    }
}
