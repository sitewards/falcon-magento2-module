<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface FilterInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface FilterInterface
{
    const LABEL = 'label';
    const CODE  = 'code';
    const OPTIONS = 'options';
    const ATTRIBUTE_ID = 'attribute_id';
    const TYPE = 'type';

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Get options
     *
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]|null
     */
    public function getOptions(): array;

    /**
     * Add option
     *
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface $option
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function addOption(FilterOptionInterface $option): FilterInterface;

    /**
     * Get attribute id
     *
     * @return int
     */
    public function getAttributeId(): int;

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string;
}
