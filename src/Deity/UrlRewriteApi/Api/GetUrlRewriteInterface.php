<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Api;

use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;

/**
 * @package Deity\UrlRewriteApi\Api
 */
interface GetUrlRewriteInterface
{
    /**
     * @param $url
     * @return \Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface
     */
    public function execute(string $url): UrlRewriteInterface;
}
