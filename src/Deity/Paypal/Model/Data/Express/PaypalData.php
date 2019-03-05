<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Data\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;

/**
 * Class PaypalData
 *
 * @package Deity\Paypal\Model\Data\Express
 */
class PaypalData implements PaypalDataInterface
{

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $url;

    /**
     * PaypalData constructor.
     * @param string $token
     * @param string $url
     */
    public function __construct(string $token = '', string $url = '')
    {
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Get Paypal token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get Paypal redirect url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
