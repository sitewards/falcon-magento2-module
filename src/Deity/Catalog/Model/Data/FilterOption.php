<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class FilterOption
 *
 * @package Deity\Catalog\Model\Data
 */
class FilterOption implements FilterOptionInterface
{

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $count;

    /**
     * FilterOption constructor.
     * @param string $label
     * @param string $value
     * @param int $count
     */
    public function __construct(string $label, string $value, int $count)
    {
        $this->label = $label;
        $this->value = $value;
        $this->count = $count;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritdoc
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
