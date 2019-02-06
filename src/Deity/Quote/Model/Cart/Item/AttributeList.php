<?php
declare(strict_types=1);

namespace Deity\Quote\Model\Cart\Item;

/**
 * Class AttributeList
 *
 * @package Deity\Quote\Model\Cart\Item
 */
class AttributeList
{
    /**
     * @var string[]
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Get Attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
