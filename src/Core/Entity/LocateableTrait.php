<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class LocateableTrait
 * @package Siqu\CMS\Core\Entity
 */
trait LocateableTrait
{
    /**
     * @var string
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     * @Groups({"api"})
     */
    protected $lat;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     * @Groups({"api"})
     */
    protected $lng;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"api"})
     */
    protected $location;

    /**
     * Retrieve the latitude.
     *
     * @return null|string
     */
    public function getLat(): ?string
    {
        return $this->lat;
    }

    /**
     * Retrieve the longitude.
     *
     * @return null|string
     */
    public function getLng(): ?string
    {
        return $this->lng;
    }

    /**
     * Retrieve the location.
     *
     * @return null|string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Set latitude.
     *
     * @param string|null $lat
     */
    public function setLat(string $lat = null): void
    {
        $this->lat = $lat;
    }

    /**
     * Set longitude.
     *
     * @param string|null $lng
     */
    public function setLng(string $lng = null): void
    {
        $this->lng = $lng;
    }

    /**
     * Set the location.
     * @param string|null $location
     */
    public function setLocation(string $location = null): void
    {
        $this->location = $location;
    }
}