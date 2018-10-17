<?php

namespace Siqu\CMS\Core\Tests\Entity\Traits;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\Traits\TimestampableTrait;

/**
 * Class TimestampableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity\Traits
 */
class TimestampableTraitTest extends TestCase
{
    /** @var TimestampableTrait */
    private $object;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = $this->getMockForTrait(TimestampableTrait::class);
    }

    /**
     * Should set and return created at.
     */
    public function testSetGetCreatedAt(): void {
        $date = new \DateTime();
        $this->assertNull($this->object->getCreatedAt());
        $this->object->setCreatedAt($date);
        $this->assertEquals($date, $this->object->getCreatedAt());
    }

    /**
     * Should set and return updated at.
     */
    public function testSetGetChangeUpdatedAt(): void {
        $date = new \DateTime();
        $this->assertNull($this->object->getUpdatedAt());
        $this->object->setUpdatedAt($date);
        $this->assertEquals($date, $this->object->getUpdatedAt());
    }
}