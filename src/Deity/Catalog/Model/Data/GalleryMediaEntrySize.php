<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface;

/**
 * Class GalleryMediaEntrySize
 *
 * @package Deity\Catalog\Model\Data
 */
class GalleryMediaEntrySize implements GalleryMediaEntrySizeInterface
{
    /**
     * @var string
     */
    private $full;

    /**
     * @var string
     */
    private $thumbnail;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $embedUrl;

    /**
     * GalleryMediaEntrySize constructor.
     * @param string $full
     * @param string $thumbnail
     * @param string $type
     * @param string $embedUrl
     */
    public function __construct(string $full, string $thumbnail, string $type, string $embedUrl)
    {
        $this->full = $full;
        $this->thumbnail = $thumbnail;
        $this->type = $type;
        $this->embedUrl = $embedUrl;
    }

    /**
     * @inheritdoc
     */
    public function getFull(): string
    {
        return $this->full;
    }

    /**
     * @inheritdoc
     */
    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getEmbedUrl(): string
    {
        return $this->embedUrl;
    }
}
