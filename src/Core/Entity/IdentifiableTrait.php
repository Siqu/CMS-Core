<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class IdentifiableTrait
 * @package Siqu\CMS\Core\Entity
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
     * @Groups({"api"})
     */
    protected $uuid;

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
     * Retrieve the uuid.
     *
     * @return null|string
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * Set the id.
     *
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Set the uuid.
     *
     * @param string $uuid
     */
    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
    }
}
