<?php
declare(strict_types=1);

namespace Deity\BreadcrumbsApi\Api;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Interface UrlRewriteBreadcrumbResolverInterface
 *
 * @package Deity\BreadcrumbsApi\Api
 */
interface UrlRewriteBreadcrumbResolverInterface
{
    /**
     * Get Breadcrumbs By Url Rewrite
     *
     * @param UrlRewrite $urlRewrite
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     */
    public function getBreadcrumbsByUrlRewrite(UrlRewrite $urlRewrite): array;
}
