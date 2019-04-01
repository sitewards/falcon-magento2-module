<?php
declare(strict_types=1);

namespace Deity\Customer\Plugin\Customer\Api;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class AccountManagement
 *
 * @package Deity\Customer\Plugin\Customer\Api
 */
class AccountManagement
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * AccountManagement constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Around plugin for initiatePasswordReset function
     *
     * @param AccountManagementInterface $subject
     * @param callable $proceed
     * @param string $email
     * @param string $template
     * @param int|null $websiteId
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundInitiatePasswordReset(
        AccountManagementInterface $subject,
        callable $proceed,
        $email,
        $template,
        $websiteId = null
    ) {
        try {
            return $proceed($email, $template, $websiteId);
        } catch (NoSuchEntityException $e) {
            return true;
        }
    }

    /**
     * Before plugin for resetPassword function
     *
     * @param AccountManagementInterface $subject
     * @param string $email
     * @param string $resetToken
     * @param string $newPassword
     * @return array
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeResetPassword(AccountManagementInterface $subject, $email, $resetToken, $newPassword)
    {
        if (ctype_digit($email)) {
            $email = $this->customerRepository->getById($email)->getEmail();
        }

        return [$email, $resetToken, $newPassword];
    }
}
