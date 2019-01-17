<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Magento\Framework\Api\AbstractSimpleObject;

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
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
