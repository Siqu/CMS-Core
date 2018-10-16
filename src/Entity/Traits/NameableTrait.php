<?php

namespace Siqu\CMSCore\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class NameableTrait
 * @package Siqu\CMSCore\Entity\Traits
 */
trait NameableTrait
{
    /**
     * @var string|null
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string|null
     * @ORM\Column(name="title_shown", type="string")
     */
    protected $titleShown;

    /**
     * Set the title.
     *
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * Retrieve the title.
     *
     * @return null|string
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * Set the title shown.
     *
     * @param string $titleShown
     */
    public function setTitleShown(string $titleShown) {
        $this->titleShown = $titleShown;
    }

    /**
     * Retrieve the title shown.
     *
     * @return null|string
     */
    public function getTitleShown(): ?string {
        return $this->titleShown;
    }
}