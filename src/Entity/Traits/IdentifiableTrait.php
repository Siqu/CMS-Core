<?php

namespace Siqu\CMS\Core\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class IdentifiableTrait
 * @package Siqu\CMS\Core\Entity\Traits
 */
trait IdentifiableTrait
{
    /**
     * @var int|null
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(name="uuid", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $uuid;

    /**
     * Set the id.
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retrieve the Id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the uuid.
     *
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * Retrieve the uuid.
     *
     * @return null|string
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }
}