<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model\Data;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterface;
use Magento\Framework\DataObject;

class UrlProcessingData extends DataObject implements UrlProcessingInterface
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
     * Getter for AddedDate.
     *
     * @return string|null
     */
    public function getAddedDate(): ?string
    {
        return $this->getData(self::ADDED_DATE);
    }

    /**
     * Setter for AddedDate.
     *
     * @param string|null $addedDate
     *
     * @return void
     */
    public function setAddedDate(?string $addedDate): void
    {
        $this->setData(self::ADDED_DATE, $addedDate);
    }
}
