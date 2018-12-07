<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface FilterOptionInterface
{
    const LABEL = 'label';
    const VALUE = 'value';
    const COUNT = 'count';

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return int
     */
    public function getCount(): int;
}
