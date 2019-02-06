<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model\Data;

use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * UrlRewrite
 *
 * @package Deity\UrlRewrite
 */
class UrlRewrite extends AbstractExtensibleObject implements UrlRewriteInterface
{
    /**
     * @inheritdoc
     */
    public function getEntityType(): string
    {
        return (string)$this->_get(self::ENTITY_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setEntityType(string $entityType): void
    {
        $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId(): string
    {
        return (string)$this->_get(self::ENTITY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setEntityId(string $id): void
    {
        $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getCanonicalUrl(): string
    {
        return (string)$this->_get(self::CANONICAL_URL);
    }

    /**
     * @inheritdoc
     */
    public function setCanonicalUrl(string $url): void
    {
        $this->setData(self::CANONICAL_URL, $url);
    }
}
