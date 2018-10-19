<?php

namespace Siqu\CMS\Core\Tests\Entity\Traits;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;

/**
 * Class IdentifiableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity\Traits
 */
class IdentifiableTraitTest extends TestCase
{
    /** @var IdentifiableTrait */
    private $object;

    /**
     * Should set and return uuid.
     */
    public function testSetGetChangeUuid(): void
    {
        $this->assertNull($this->object->getUuid());
        $this->object->setUuid('1');
        $this->assertEquals('1', $this->object->getUuid());
    }

    /**
     * Should set and return id.
     */
    public function testSetGetId(): void
    {
        $this->assertNull($this->object->getId());
        $this->object->setId(1);
        $this->assertEquals(1, $this->object->getId());
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = $this->getMockForTrait(IdentifiableTrait::class);
    }
}