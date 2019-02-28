<?php
declare(strict_types=1);

namespace Deity\Paypal\Model;

use Deity\PaypalApi\Api\Data\PaypalDataInterface;
use Deity\PaypalApi\Api\PaypalInterface;

/**
 * Class PaypalExpress
 *
 * @package Deity\Paypal\Model
 */
class PaypalExpress implements PaypalInterface
{
    /**
     * @var PaypalExpressProcessorInterface
     */
    private $paypalExpressProcessor;

    /**
     * PaypalExpress constructor.
     * @param PaypalExpressProcessorInterface $paypalExpressProcessor
     */
    public function __construct(PaypalExpressProcessorInterface $paypalExpressProcessor)
    {
        $this->paypalExpressProcessor = $paypalExpressProcessor;
    }

    /**
     * Get Token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\PaypalDataInterface
     */
    public function getToken(string $cartId): PaypalDataInterface
    {
        return $this->paypalExpressProcessor->createPaypalData($cartId);
    }
}
