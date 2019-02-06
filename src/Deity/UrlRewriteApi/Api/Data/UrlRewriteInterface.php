<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Api\Data;

/**
 * Interface UrlRewriteInterface
 *
 * @package Deity\UrlRewriteApi\Api\Data
 */
interface UrlRewriteInterface
{
    const ENTITY_TYPE = 'entity_type';
    const ENTITY_ID   = 'entity_id';
    const CANONICAL_URL = 'canonical_url';

    /**
     * Get entity type
     *
     * @return string
     */
    public function getEntityType(): string;

    /**
     * Set entity type
     *
     * @param string $entityType
     * @return void
     */
    public function setEntityType(string $entityType): void;

    /**
     * Get entity id
     *
     * @return string
     */
    public function getEntityId(): string;

    /**
     * Set entity id
     *
     * @param string $id
     * @return void
     */
    public function setEntityId(string $id): void;

    /**
     * Get canonical url
     *
     * @return string
     */
    public function getCanonicalUrl(): string;

    /**
     * Set canonical url
     *
     * @param string $url
     * @return void
     */
    public function setCanonicalUrl(string $url): void;
}
