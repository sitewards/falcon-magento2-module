<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Phrase;

class Filter implements FilterInterface
{

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $code;

    /**
     * @var int
     */
    private $attributeId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $options = [];

    /**
     * Filter constructor.
     * @param string $label
     * @param string $code
     * @param int $attributeId
     * @param string $type
     * @param array $options
     */
    public function __construct(string $label, string $code, int $attributeId, string $type, array $options = [])
    {
        $this->label = $label;
        $this->code = $code;
        $this->attributeId = $attributeId;
        $this->type = $type;
        $this->options = $options;
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getAttributeId(): int
    {
        return $this->attributeId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface $option
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function addOption(FilterOptionInterface $option): FilterInterface
    {
        $this->options[] = $option;
        return $this;
    }
}
