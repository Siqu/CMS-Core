<?php

namespace Siqu\CMS\Core\Tests\Entity\Traits;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\Traits\NameableTrait;

/**
 * Class NameableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity\Traits
 */
class NameableTraitTest extends TestCase
{
    /** @var NameableTrait */
    private $object;

    /**
     * Should set and return title shown.
     */
    public function testSetGetChangeTitleShown(): void
    {
        $this->assertNull($this->object->getTitleShown());
        $this->object->setTitleShown('titleShown');
        $this->assertEquals('titleShown', $this->object->getTitleShown());
    }

    /**
     * Should set and return title.
     */
    public function testSetGetTitle(): void
    {
        $this->assertNull($this->object->getTitle());
        $this->object->setTitle('title');
        $this->assertEquals('title', $this->object->getTitle());
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