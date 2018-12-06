<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Api;

use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;

/**
 * Interface ConvertEntityIdToUniqueKey
 * @package Deity\UrlRewriteApi\Api
 */
interface ConvertEntityIdToUniqueKeyInterface
{

    /**
     * @param UrlRewriteInterface $rewrite
     * @return void
     */
    public function execute(UrlRewriteInterface $rewrite): void;
}
