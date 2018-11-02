<?php

namespace Siqu\CMS\Core\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\IdentifiableTrait;

/**
 * Class IdentifiableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity
 * @IdentifiableTrait
 */
class IdentifiableTraitTest extends TestCase
{
    /** @var IdentifiableTrait */
    private $object;

    /**
     * Should set and return id.
     * @IdentifiableTrait::setId
     * @IdentifiableTrait::getId
     */
    public function testSetGetId(): void
    {
        $this->assertNull($this->object->getId());
        $this->object->setId(1);
        $this->assertEquals(1, $this->object->getId());
    }

    /**
     * Should set and return uuid.
     * @IdentifiableTrait::setUuid
     * @IdentifiableTrait::getUuid
     */
    public function testSetGetUuid(): void
    {
        $this->assertNull($this->object->getUuid());
        $this->object->setUuid('1');
        $this->assertEquals('1', $this->object->getUuid());
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