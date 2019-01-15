<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface GalleryMediaEntrySizeInterface
{

    const TYPE = 'type';
    const FULL = 'full';
    const THUMBNAIL = 'thumbnail';
    const EMBED_URL = 'embedUrl';

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getFull(): string;

    /**
     * @return string
     */
    public function getThumbnail(): string;

    /**
     * @return string
     */
    public function getEmbedUrl(): string;
}
