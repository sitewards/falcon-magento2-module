<?php
declare(strict_types=1);

namespace Deity\BreadcrumbsApi\Api;

/**
 * @package Deity\BreadcrumbsApi\Api
 */
interface GetBreadcrumbsInterface
{
    /**
     * @param string $url
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     */
    public function execute(string $url): array;
}
