<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Api;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

interface CanonicalUrlProviderInterface
{
    const BASE_KEY = 'base';

    /**
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $urlModel
     * @return string
     */
    public function getCanonicalUrl(UrlRewrite $urlModel): string;
}
