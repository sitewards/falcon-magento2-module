<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;
use Deity\PaypalApi\Api\Express\TokenProviderInterface;

/**
 * Class Paypal
 *
 * @package Deity\Paypal\Model
 */
class TokenProvider implements TokenProviderInterface
{
    /**
     * @var PaypalManagementInterface
     */
    private $paypalManagement;

    /**
     * PaypalExpress constructor.
     * @param PaypalManagementInterface $paypalManagement
     */
    public function __construct(PaypalManagementInterface $paypalManagement)
    {
        $this->paypalManagement = $paypalManagement;
    }

    /**
     * Get Token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\PaypalDataInterface
     */
    public function getToken(string $cartId): PaypalDataInterface
    {
        return $this->paypalManagement->createPaypalData($cartId);
    }
}
