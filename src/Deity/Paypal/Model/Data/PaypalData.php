<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Data;

use Deity\PaypalApi\Api\Data\PaypalDataInterface;

/**
 * Class PaypalData
 *
 * @package Deity\Paypal\Model\Data
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
    private $error;

    /**
     * @var string
     */
    private $url;

    /**
     * PaypalData constructor.
     * @param string $token
     * @param string $error
     * @param string $url
     */
    public function __construct(string $token = '', string $error = '', string $url = '')
    {
        $this->token = $token;
        $this->error = $error;
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

    /**
     * Get Paypal error
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}
