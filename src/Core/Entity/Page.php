<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siqu\CMS\Core\Entity\Traits\BlameableTrait;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Siqu\CMS\Core\Entity\Traits\NameableTrait;
use Siqu\CMS\Core\Entity\Traits\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Page
 * @package Siqu\CMS\Core\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cms_page")
 * @UniqueEntity("title", groups={"new", "update"})
 */
class Page
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;
    use BlameableTrait;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Siqu\CMS\Core\Entity\Page", mappedBy="parent", cascade={"persist"})
     * @Groups({"api"})
     */
    private $children;
    /**
     * @var Page
     * @ORM\ManyToOne(targetEntity="Siqu\CMS\Core\Entity\Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Groups({"api"})
     */
    private $parent;
    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"api"})
     */
    private $slug;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Add a child.
     *
     * @param Page $child
     */
    public function addChild(Page $child): void
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
        }
    }

    /**
     * Retrieve the children.
     *
     * @return ArrayCollection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * Get the parent.
     *
     * @return null|Page
     */
    public function getParent(): ?Page
    {
        return $this->parent;
    }

    /**
     * Retrieve the slug.
     *
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Remove a child.
     *
     * @param Page $child
     */
    public function removeChild(Page $child): void
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
        }
    }

    /**
     * Set the children.
     *
     * @param ArrayCollection $children
     */
    public function setChildren(ArrayCollection $children): void
    {
        $this->children = $children;
    }

    /**
     * Set the parent.
     *
     * @param Page $parent
     */
    public function setParent(Page $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * Retrieve the slug.
     *
     * @param $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }
}