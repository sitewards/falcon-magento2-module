<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Data\Express;

use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;

/**
 * Class RedirectData
 *
 * @package Deity\Paypal\Model\Data\Express
 */
class RedirectData implements RedirectDataInterface
{

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $realOrderId;

    /**
     * @var string
     */
    private $uenc;

    /**
     * RedirectData constructor.
     *
     * @param string $redirectUrl
     * @param string $uenc
     * @param string $orderId
     * @param string $realOrderId
     */
    public function __construct(string $redirectUrl, string $uenc, string $orderId = '', string $realOrderId = '')
    {
        $this->redirectUrl = $redirectUrl;
        $this->orderId = $orderId;
        $this->realOrderId = $realOrderId;
        $this->uenc = $uenc;
    }

    /**
     * Get Redirect URL
     *
     * @return string
     */
    public function getRedirect(): string
    {
        return $this->redirectUrl;
    }

    /**
     * Get order Id
     *
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * Get Order increment Id
     *
     * @return string
     */
    public function getRealOrderId(): string
    {
        return $this->realOrderId;
    }

    /**
     * Get encoded payment message
     *
     * @return string
     */
    public function getUenc(): string
    {
        return $this->uenc;
    }
}
