<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Api\Data;

interface UrlListInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const URL = "url";
    const PRIO = "prio";

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
     * Getter for Prio.
     *
     * @return int|null
     */
    public function getPrio(): ?int;

    /**
     * Setter for Prio.
     *
     * @param int|null $prio
     *
     * @return void
     */
    public function setPrio(?int $prio): void;
}
