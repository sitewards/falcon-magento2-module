<?php
declare(strict_types=1);

namespace Deity\Paypal\Model;

use Deity\PaypalApi\Api\Data\PaypalDataInterface;

/**
 * Class PaypalExpressProcessorInterface
 *
 * @package Deity\Paypal\Model
 */
interface PaypalExpressProcessorInterface
{

    /**
     * Create paypal token
     *
     * @param string $cartId
     * @return PaypalDataInterface
     */
    public function createPaypalData(string $cartId): PaypalDataInterface;
}
