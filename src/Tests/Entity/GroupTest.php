<?php

namespace Siqu\CMS\Core\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\Group;

/**
 * Class GroupTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class GroupTest extends TestCase
{
    /** @var Group */
    private $object;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new Group('name');
    }

    /**
     * Should set and get name
     */
    public function testSetGetName(): void
    {
        $this->assertEquals('name', $this->object->getName());
        $this->object->setName('name1');
        $this->assertEquals('name1', $this->object->getName());
    }

    /**
     * Should add, set, get and remove roles
     */
    public function testAddSetGetRemoveRoles(): void
    {
        $this->assertEmpty($this->object->getRoles());
        $this->object->addRole('role1');
        $this->object->addRole('role1');
        $this->assertCount(1, $this->object->getRoles());
        $this->assertEquals([
            'ROLE1'
        ], $this->object->getRoles());
        $this->object->removeRole('role1');
        $this->object->removeRole('role1');
        $this->assertEmpty($this->object->getRoles());

        $this->object->setRoles(['role1']);
        $this->assertCount(1, $this->object->getRoles());
        $this->assertEquals([
            'ROLE1'
        ], $this->object->getRoles());
    }
}
