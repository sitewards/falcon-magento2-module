<?php
declare(strict_types=1);

namespace Deity\Breadcrumbs\Model\Data;

use Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface;

/**
 * Class Breadcrumb
 *
 * @package Deity\Breadcrumbs\Model\Data
 */
class Breadcrumb implements BreadcrumbInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $urlPath;

    /**
     * Breadcrumb constructor.
     * @param string $name
     * @param string $urlPath
     */
    public function __construct(string $name, string $urlPath)
    {
        $this->name = $name;
        $this->urlPath = $urlPath;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getUrlPath(): string
    {
        return $this->urlPath;
    }
}
