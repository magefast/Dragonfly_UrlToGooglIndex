<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model\Data;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterface;
use Magento\Framework\DataObject;

class UrlList extends DataObject implements UrlListInterface
{
    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID) === null ? null
            : (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void
    {
        $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Getter for Url.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::URL);
    }

    /**
     * Setter for Url.
     *
     * @param string|null $url
     *
     * @return void
     */
    public function setUrl(?string $url): void
    {
        $this->setData(self::URL, $url);
    }

    /**
     * Getter for Prio.
     *
     * @return int|null
     */
    public function getPrio(): ?int
    {
        return $this->getData(self::PRIO) === null ? null
            : (int)$this->getData(self::PRIO);
    }

    /**
     * Setter for Prio.
     *
     * @param int|null $prio
     *
     * @return void
     */
    public function setPrio(?int $prio): void
    {
        $this->setData(self::PRIO, $prio);
    }
}
