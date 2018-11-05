<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NameableTrait
 * @package Siqu\CMS\Core\Entity
 */
trait NameableTrait
{
    /**
     * @var string|null
     * @ORM\Column(name="title", type="string", unique=true)
     * @Groups({"api"})
     * @Assert\NotBlank(groups={"new"})
     */
    protected $title;

    /**
     * @var string|null
     * @ORM\Column(name="title_shown", type="string", nullable=true)
     * @Groups({"api"})
     */
    protected $titleShown;

    /**
     * Retrieve the title.
     *
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Retrieve the title shown.
     *
     * @return null|string
     */
    public function getTitleShown(): ?string
    {
        return $this->titleShown;
    }

    /**
     * Set the title.
     *
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Set the title shown.
     *
     * @param string $titleShown
     */
    public function setTitleShown($titleShown): void
    {
        $this->titleShown = $titleShown;
    }
}
