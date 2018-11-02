<?php

namespace Siqu\CMS\Core\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\NameableTrait;

/**
 * Class NameableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class NameableTraitTest extends TestCase
{
    /** @var NameableTrait */
    private $object;

    /**
     * Should set and return title.
     * @NameableTrait::setTitle
     * @NameableTrait::getTitle
     */
    public function testSetGetTitle(): void
    {
        $this->assertNull($this->object->getTitle());
        $this->object->setTitle('title');
        $this->assertEquals('title', $this->object->getTitle());
    }

    /**
     * Should set and return title shown.
     * @NameableTrait::setTitleShown
     * @NameableTrait::getTitleShown
     */
    public function testSetGetTitleShown(): void
    {
        $this->assertNull($this->object->getTitleShown());
        $this->object->setTitleShown('titleShown');
        $this->assertEquals('titleShown', $this->object->getTitleShown());
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = $this->getMockForTrait(NameableTrait::class);
    }
}