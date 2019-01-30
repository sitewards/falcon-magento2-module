<?php
/**
 * Created by Ryan Copeland <ryan@ryancopeland.co.uk>.
 * User: ryancopeland
 * Date: 2019-01-05
 * Time: 15:55
 */

namespace Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider;

use Deity\UrlRewriteApi\Api\CanonicalUrlProviderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class BaseUrlProvider implements CanonicalUrlProviderInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $urlModel
     * @return string
     */
    public function getCanonicalUrl(UrlRewrite $urlModel)
    {
        return $this->urlBuilder->getDirectUrl($urlModel->getRequestPath());
    }
}
