<?php

namespace Siqu\CMS\Core\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\BlameableTrait;
use Siqu\CMS\Core\Entity\IdentifiableTrait;
use Siqu\CMS\Core\Entity\LocateableTrait;
use Siqu\CMS\Core\Entity\NameableTrait;
use Siqu\CMS\Core\Entity\Page;
use Siqu\CMS\Core\Entity\TimestampableTrait;

/**
 * Class PageTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class PageTest extends TestCase
{
    /** @var Page */
    private $object;

    /**
     * Should add, get, remove and set children.
     */
    public function testAddGetSetRemoveChild(): void
    {
        $child = new Page();
        $this->assertEmpty($this->object->getChildren());
        $this->object->addChild($child);
        $this->object->addChild($child);
        $this->assertCount(
            1,
            $this->object->getChildren()
        );
        $this->object->removeChild($child);
        $this->object->removeChild($child);
        $this->assertEmpty($this->object->getChildren());

        $collection = new ArrayCollection(
            [
                $child
            ]
        );
        $this->object->setChildren($collection);
        $this->assertEquals(
            $collection,
            $this->object->getChildren()
        );
    }

    /**
     * Should create proper instance.
     * @throws \ReflectionException
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            Page::class,
            $this->object
        );
        $this->assertEquals(
            Page::VISIBILITY_NAVIGATION_CONTENT,
            $this->object->getVisibility()
        );

        $reflection = new \ReflectionClass($this->object);
        $traits = $reflection->getTraitNames();

        $this->assertEquals(
            [
                IdentifiableTrait::class,
                NameableTrait::class,
                TimestampableTrait::class,
                BlameableTrait::class,
                LocateableTrait::class
            ],
            $traits
        );
    }

    /**
     * Should return title.
     */
    public function testGetMetaTitleEmpty(): void
    {
        $this->object->setTitle('title');

        $this->assertEquals(
            'title',
            $this->object->getMetaTitle()
        );
    }

    /**
     * Should get and set metaDescription.
     */
    public function testGetSetMetaDescription(): void
    {
        $this->assertNull($this->object->getMetaDescription());
        $this->object->setMetaDescription('metaDescription');
        $this->assertEquals(
            'metaDescription',
            $this->object->getMetaDescription()
        );
    }

    /**
     * Should get and set metaTitle.
     */
    public function testGetSetMetaTitle(): void
    {
        $this->assertNull($this->object->getMetaTitle());
        $this->object->setMetaTitle('metaTitle');
        $this->assertEquals(
            'metaTitle',
            $this->object->getMetaTitle()
        );
    }

    /**
     * Should get and set parent
     */
    public function testGetSetParent(): void
    {
        $parent = new Page();

        $this->assertNull($this->object->getParent());
        $this->object->setParent($parent);
        $this->assertEquals(
            $parent,
            $this->object->getParent()
        );
    }

    /**
     * Should get and set slug
     */
    public function testGetSetSlug(): void
    {
        $this->assertNull($this->object->getSlug());
        $this->object->setSlug('slug');
        $this->assertEquals(
            'slug',
            $this->object->getSlug()
        );
    }

    /**
     * Should get and set visibility
     */
    public function testGetSetVisibility(): void
    {
        $this->object->setVisibility(Page::VISIBILITY_NAVIGATION);
        $this->assertEquals(
            Page::VISIBILITY_NAVIGATION,
            $this->object->getVisibility()
        );
    }

    /**
     * Should not change visibility.
     */
    public function testSetVisibilityInvalid(): void
    {
        $this->object->setVisibility(-1);

        $this->assertEquals(
            Page::VISIBILITY_NAVIGATION_CONTENT,
            $this->object->getVisibility()
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new Page();
    }
}
