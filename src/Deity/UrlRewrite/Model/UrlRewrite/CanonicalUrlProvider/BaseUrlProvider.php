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

/**
 * Class BaseUrlProvider
 *
 * @package Deity\UrlRewrite\Model\UrlRewrite\CanonicalUrlProvider
 */
class BaseUrlProvider implements CanonicalUrlProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getCanonicalUrl(UrlRewrite $urlModel)
    {
        return $urlModel->getRequestPath();
    }
}
