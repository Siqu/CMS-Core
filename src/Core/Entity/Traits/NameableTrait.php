<?php

namespace Siqu\CMS\Core\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NameableTrait
 * @package Siqu\CMS\Core\Entity\Traits
 */
trait NameableTrait
{
    /**
     * @var string|null
     * @ORM\Column(name="title", type="string")
     * @Groups({"api"})
     * @Assert\NotBlank(groups={"new", "update"})
     */
    protected $title;

    /**
     * @var string|null
     * @ORM\Column(name="title_shown", type="string")
     * @Groups({"api"})
     */
    protected $titleShown;

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
     * Retrieve the title.
     *
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
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

    /**
     * Retrieve the title shown.
     *
     * @return null|string
     */
    public function getTitleShown(): ?string
    {
        return $this->titleShown;
    }
}