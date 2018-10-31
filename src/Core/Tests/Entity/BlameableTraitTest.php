<?php

namespace Siqu\CMS\Core\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Entity\BlameableTrait;

/**
 * Class BlameableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class BlameableTraitTest extends TestCase
{
    /** @var BlameableTrait */
    private $object;

    /**
     * Should set and return change user.
     */
    public function testSetGetChangeUser(): void
    {
        $user = new CMSUser();

        $this->assertNull($this->object->getChangeUser());
        $this->object->setChangeUser($user);
        $this->assertEquals($user, $this->object->getChangeUser());
    }

    /**
     * Should set and return user.
     */
    public function testSetGetUser(): void
    {
        $user = new CMSUser();

        $this->assertNull($this->object->getUser());
        $this->object->setUser($user);
        $this->assertEquals($user, $this->object->getUser());
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = $this->getMockForTrait(BlameableTrait::class);
    }
}