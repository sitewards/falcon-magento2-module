<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Magento\Quote\Api\Data\CartInterface;

/**
 * Interface RedirectToFalconProviderInterface
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
interface RedirectToFalconProviderInterface
{
    /**
     * Get success url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getSuccessUrl(CartInterface $quote): string;

    /**
     * Get cancel url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getCancelUrl(CartInterface $quote): string;

    /**
     * Get failure url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getFailureUrl(CartInterface $quote): string;

    /**
     * Get paypal return success url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getPaypalReturnSuccessUrl(CartInterface $quote): string;

    /**
     * Get paypal return cancel url
     *
     * @param CartInterface $quote
     * @return string
     */
    public function getPaypalReturnCancelUrl(CartInterface $quote): string;
}
