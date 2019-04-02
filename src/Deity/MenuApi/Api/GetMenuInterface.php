<?php
declare(strict_types=1);

namespace Deity\MenuApi\Api;

/**
 * Interface GetMenuInterface
 *
 * @package Deity\MenuApi\Api
 * @api
 */
interface GetMenuInterface
{
    /**
     * Get menu
     *
     * @return \Deity\MenuApi\Api\Data\MenuInterface[]
     */
    public function execute(): array;
}
