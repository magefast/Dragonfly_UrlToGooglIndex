<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Api\Data;

interface UrlProcessingInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const URL = "url";
    const ADDED_DATE = "added_date";

    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Getter for Url.
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Setter for Url.
     *
     * @param string|null $url
     *
     * @return void
     */
    public function setUrl(?string $url): void;

    /**
     * Getter for AddedDate.
     *
     * @return string|null
     */
    public function getAddedDate(): ?string;

    /**
     * Setter for AddedDate.
     *
     * @param string|null $addedDate
     *
     * @return void
     */
    public function setAddedDate(?string $addedDate): void;
}
