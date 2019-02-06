<?php
declare(strict_types=1);

namespace Deity\BreadcrumbsApi\Api\Data;

/**
 * Interface BreadcrumbInterface
 *
 * @package Deity\BreadcrumbsApi\Api\Data
 */
interface BreadcrumbInterface
{
    const NAME = 'name';
    const URL_PATH = 'urlPath';

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get Url Path
     *
     * @return string
     */
    public function getUrlPath(): string;
}
