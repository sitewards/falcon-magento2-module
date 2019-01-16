<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider;

use Deity\UrlRewriteApi\Api\CanonicalUrlProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class BaseUrlProvider implements CanonicalUrlProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * BaseUrlProvider constructor.
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param UrlRewrite $urlModel
     * @return string
     */
    public function getCanonicalUrl(UrlRewrite $urlModel): string
    {
        return $this->urlBuilder->getDirectUrl($urlModel->getRequestPath());
    }
}
