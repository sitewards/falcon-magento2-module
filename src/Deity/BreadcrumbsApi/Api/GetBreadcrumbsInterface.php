<?php
declare(strict_types=1);

namespace Deity\BreadcrumbsApi\Api;

/**
 * GetBreadcrumbsInterface Functional object
 *
 * @package Deity\BreadcrumbsApi\Api
 */
interface GetBreadcrumbsInterface
{
    /**
     * Execute method
     *
     * @param string $url
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     */
    public function execute(string $url): array;
}
