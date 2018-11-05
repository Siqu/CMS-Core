<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class TimestampableTrait
 * @package Siqu\CMS\Core\Entity
 */
trait TimestampableTrait
{
    /**
     * @var \DateTime|null
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"api"})
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"api"})
     */
    private $updatedAt;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
