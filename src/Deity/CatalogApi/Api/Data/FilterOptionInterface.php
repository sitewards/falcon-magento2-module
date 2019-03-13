<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface FilterOptionInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface FilterOptionInterface
{
    const LABEL = 'label';
    const VALUE = 'value';
    const COUNT = 'count';
    const IS_SELECTED = 'isSelected';

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Get count
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Get selected flag
     *
     * @return bool
     */
    public function getIsSelected(): bool;
}
