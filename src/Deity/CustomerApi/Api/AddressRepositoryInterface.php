<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Api;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface AddressRepositoryInterface
 *
 * @package Deity\CustomerApi\Api
 */
interface AddressRepositoryInterface
{
    /**
     * Get customer address list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Magento\Customer\Api\Data\AddressSearchResultsInterface
     */
    public function getCustomerAddressList(SearchCriteriaInterface $searchCriteria = null);

    /**
     * Get customer address
     *
     * @param int $addressId
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function getCustomerAddress($addressId);

    /**
     * Create customer address
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function createCustomerAddress(AddressInterface $address);

    /**
     * Update customer address
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function updateCustomerAddress(AddressInterface $address);

    /**
     * Delete customer address
     *
     * @param int $addressId
     * @return bool
     */
    public function deleteCustomerAddress($addressId);
}
