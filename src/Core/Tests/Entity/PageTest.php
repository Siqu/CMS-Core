<?php

namespace Siqu\CMS\Core\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\Page;

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
        $this->assertCount(1, $this->object->getChildren());
        $this->object->removeChild($child);
        $this->object->removeChild($child);
        $this->assertEmpty($this->object->getChildren());

        $collection = new ArrayCollection([
            $child
        ]);
        $this->object->setChildren($collection);
        $this->assertEquals($collection, $this->object->getChildren());
    }

    /**
     * Should create proper instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(Page::class, $this->object);
    }

    /**
     * Should get and set parent
     */
    public function testGetSetParent(): void
    {
        $parent = new Page();

        $this->assertNull($this->object->getParent());
        $this->object->setParent($parent);
        $this->assertEquals($parent, $this->object->getParent());
    }

    /**
     * Should get and set slug
     */
    public function testGetSetSlug(): void
    {
        $this->assertNull($this->object->getSlug());
        $this->object->setSlug('slug');
        $this->assertEquals('slug', $this->object->getSlug());
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
