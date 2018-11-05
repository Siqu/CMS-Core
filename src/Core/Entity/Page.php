<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
    use LocateableTrait;

    /**
     * Available visibilities.
     *
     * @var array
     */
    const VISIBILITIES = [
        self::VISIBILITY_NAVIGATION_CONTENT,
        self::VISIBILITY_NAVIGATION,
        self::VISIBILITY_CONTENT,
        self::VISIBILITY_HIDDEN
    ];
    /**
     * Display in content only.
     * @var int
     */
    const VISIBILITY_CONTENT = 2;
    /**
     * Display never.
     * @var int
     */
    const VISIBILITY_HIDDEN = 3;
    /**
     * Display in navigation only.
     * @var int
     */
    const VISIBILITY_NAVIGATION = 1;
    /**
     * Display in navigation and content
     * @var int
     */
    const VISIBILITY_NAVIGATION_CONTENT = 0;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Siqu\CMS\Core\Entity\Page", mappedBy="parent", cascade={"persist"})
     * @Groups({"api"})
     */
    private $children;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"api"})
     */
    private $metaDescription;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"api"})
     */
    private $metaTitle;
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
     * @var integer
     * @ORM\Column(type="smallint")
     * @Groups({"api"})
     */
    private $visibility;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->visibility = self::VISIBILITY_NAVIGATION_CONTENT;
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
     * Retrieve the metaDescription.
     *
     * @return null|string
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * Retrieve the meta title.
     * Falls back to title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle ? $this->metaTitle : $this->title;
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
     * Retrieve the visibility.
     *
     * @return int
     */
    public function getVisibility(): int
    {
        return $this->visibility;
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
     * Set the metaDescription.
     *
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Set the meta title.
     * @param string $metaTitle
     */
    public function setMetaTitle($metaTitle): void
    {
        $this->metaTitle = $metaTitle;
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

    /**
     * Set visibility.
     *
     * @param int $visibility
     */
    public function setVisibility(int $visibility): void
    {
        if (in_array($visibility, self::VISIBILITIES)) {
            $this->visibility = $visibility;
        }
    }
}
