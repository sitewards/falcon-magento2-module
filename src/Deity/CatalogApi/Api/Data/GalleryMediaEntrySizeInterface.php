<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface GalleryMediaEntrySizeInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface GalleryMediaEntrySizeInterface
{

    const TYPE = 'type';
    const FULL = 'full';
    const THUMBNAIL = 'thumbnail';
    const EMBED_URL = 'embedUrl';

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get full
     *
     * @return string
     */
    public function getFull(): string;

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail(): string;

    /**
     * Get embed url
     *
     * @return string
     */
    public function getEmbedUrl(): string;
}
