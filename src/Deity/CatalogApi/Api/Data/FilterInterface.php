<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface FilterInterface
{
    const LABEL = 'label';
    const CODE  = 'code';
    const OPTIONS = 'options';
    const ATTRIBUTE_ID = 'attribute_id';
    const TYPE = 'type';

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]|null
     */
    public function getOptions(): array;

    /**
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface $option
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function addOption(FilterOptionInterface $option): FilterInterface;

    /**
     * @return int
     */
    public function getAttributeId(): int;

    /**
     * @return string
     */
    public function getType(): string;
}
