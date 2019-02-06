<?php
/**
 * Created by Ryan Copeland <ryan@ryancopeland.co.uk>.
 * User: ryancopeland
 * Date: 2019-01-05
 * Time: 15:57
 */

namespace Deity\UrlRewriteApi\Api;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Interface CanonicalUrlProviderInterface
 * @package Deity\UrlRewriteApi\Api
 */
interface CanonicalUrlProviderInterface
{
    /**
     * Provide canonical url for given UrlRewrite object
     *
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $urlModel
     * @return string
     */
    public function getCanonicalUrl(UrlRewrite $urlModel);
}
