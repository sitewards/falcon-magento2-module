<?php
declare(strict_types=1);

namespace Deity\Breadcrumbs\Model\GetBreadcrumbs\Command;

use Deity\BreadcrumbsApi\Api\UrlRewriteBreadcrumbResolverInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class GetBreadcrumbsForCmsPage
 * @package Deity\Breadcrumbs\Model\GetBreadcrumbs\Command
 */
class GetBreadcrumbsForCmsPage implements UrlRewriteBreadcrumbResolverInterface
{
    /**
     * @param UrlRewrite $urlRewrite
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     */
    public function getBreadcrumbsByUrlRewrite(UrlRewrite $urlRewrite): array
    {
        // Magento CE doesn't have CMS hierarchy feature. Class introduced for future reference
        return [];
    }
}
