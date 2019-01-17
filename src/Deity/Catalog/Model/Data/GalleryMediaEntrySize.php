<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface;

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
     * @return string
     */
    public function getFull(): string
    {
        return $this->full;
    }

    /**
     * @return string
     */
    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getEmbedUrl(): string
    {
        return $this->embedUrl;
    }
}
