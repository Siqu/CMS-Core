<?php

namespace Siqu\CMS\Core\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Entity\Group;

/**
 * Class CMSUserTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class CMSUserTest extends TestCase
{
    /** @var CMSUser */
    private $object;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new CMSUser();
    }

    /**
     * Should create instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(CMSUser::class, $this->object);
        $this->assertFalse($this->object->isEnabled());
        $this->assertEquals([CMSUser::ROLE_DEFAULT], $this->object->getRoles());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getGroups());
        $this->assertEmpty($this->object->getGroups());
    }

    /**
     * Should set and get username.
     */
    public function testSetGetUsername(): void
    {
        $this->assertNull($this->object->getUsername());
        $this->object->setUsername('username');
        $this->assertEquals('username', $this->object->getUsername());
    }

    /**
     * Should set and get password.
     */
    public function testSetGetPassword(): void
    {
        $this->assertNull($this->object->getPassword());
        $this->object->setPassword('password');
        $this->assertEquals('password', $this->object->getPassword());
    }

    /**
     * Should set and get plain password.
     */
    public function testSetGetPlainPassword(): void
    {
        $this->assertNull($this->object->getPlainPassword());
        $this->object->setPlainPassword('plainpassword');
        $this->assertEquals('plainpassword', $this->object->getPlainPassword());
    }

    /**
     * Should set and get plain email.
     */
    public function testSetGetEmail(): void
    {
        $this->assertNull($this->object->getEmail());
        $this->object->setEmail('email');
        $this->assertEquals('email', $this->object->getEmail());
    }

    /**
     * Should set and get enabled.
     */
    public function testSetGetEnabled(): void
    {
        $this->assertFalse($this->object->isEnabled());
        $this->object->setEnabled(true);
        $this->assertTrue($this->object->isEnabled());
    }

    /**
     * Should set and get confirmation token.
     */
    public function testSetGetConfirmationToken(): void
    {
        $this->assertNull($this->object->getConfirmationToken());
        $this->object->setConfirmationToken('confirmationtoken');
        $this->assertEquals('confirmationtoken', $this->object->getConfirmationToken());
    }

    /**
     * Should set and get last login.
     */
    public function testSetGetLastLogin(): void
    {
        $date = new \DateTime();
        $this->assertNull($this->object->getLastLogin());
        $this->object->setLastLogin($date);
        $this->assertEquals($date, $this->object->getLastLogin());
    }

    /**
     * Should return null.
     */
    public function testGetSalt(): void
    {
        $this->assertNull($this->object->getSalt());
    }

    /**
     * Should add, get and remove groups.
     */
    public function testAddGetSetRemoveGroups(): void
    {
        $group = new Group('name');

        $this->assertEmpty($this->object->getGroups());
        $this->object->addGroup($group);
        $this->object->addGroup($group);
        $this->assertCount(1, $this->object->getGroups());
        $this->object->removeGroup($group);
        $this->object->removeGroup($group);
        $this->assertEmpty($this->object->getGroups());

        $groups = new ArrayCollection();
        $groups->add($group);
        $this->object->setGroups($groups);
        $this->assertCount(1, $this->object->getGroups());
    }

    /**
     * Should return group names
     */
    public function testGetGroupNames(): void
    {
        $group1 = new Group('name1');
        $group2 = new Group('name2');
        $this->object->addGroup($group1);
        $this->object->addGroup($group2);

        $this->assertEquals([
            'name1',
            'name2'
        ], $this->object->getGroupNames());
    }

    /**
     * Should check if group exists
     */
    public function testHasGroup(): void
    {
        $group = new Group('name');
        $this->object->addGroup($group);

        $this->assertTrue($this->object->hasGroup('name'));
        $this->assertFalse($this->object->hasGroup('name2'));
    }

    /**
     * Should add, set and remove roles.
     */
    public function testAddSetRemoveRoles(): void
    {
        $this->assertCount(1, $this->object->getRoles());
        $this->assertEquals([CMSUser::ROLE_DEFAULT], $this->object->getRoles());
        $this->object->addRole('role1');
        $this->object->addRole(CMSUser::ROLE_DEFAULT);
        $this->assertCount(2, $this->object->getRoles());
        $this->assertEquals(['ROLE1', CMSUser::ROLE_DEFAULT], $this->object->getRoles());
        $this->object->removeRole('role1');
        $this->assertCount(1, $this->object->getRoles());
        $this->assertEquals([CMSUser::ROLE_DEFAULT], $this->object->getRoles());

        $this->object->setRoles(['role1']);
        $this->assertCount(2, $this->object->getRoles());
        $this->assertEquals(['ROLE1', CMSUser::ROLE_DEFAULT], $this->object->getRoles());
    }

    /**
     * Should merge roles and groups.
     */
    public function testGetRoles(): void
    {
        $group = new Group('name');
        $group->addRole('role1');
        $this->object->addGroup($group);
        $this->object->addRole('role1');

        $this->assertCount(2, $this->object->getRoles());
        $this->assertEquals([
            'ROLE1',
            CMSUser::ROLE_DEFAULT
        ], $this->object->getRoles());
    }

    /**
     * Should check if user has role
     */
    public function testHasRole(): void
    {
        $this->assertTrue($this->object->hasRole(CMSUser::ROLE_DEFAULT));
        $this->assertFalse($this->object->hasRole('role1'));
    }

    /**
     * Should enable and disable super admin.
     */
    public function testSetGetSuperAdmin(): void
    {
        $this->assertFalse($this->object->isSuperAdmin());
        $this->object->setSuperAdmin(true);
        $this->assertTrue($this->object->isSuperAdmin());
        $this->object->setSuperAdmin(false);
        $this->assertFalse($this->object->isSuperAdmin());
    }

    /**
     * Should serialize user
     */
    public function testSerialize(): void
    {
        $user = new CMSUser();
        $user->setId(1);
        $user->setUuid('uuid');
        $user->setUsername('username');
        $user->setPassword('password');
        $user->setEmail('mail');
        $user->setEnabled(true);

        $serialized = $user->serialize();

        $this->assertEquals(
            'a:6:{i:0;i:1;i:1;s:4:"uuid";i:2;s:8:"username";i:3;s:8:"password";i:4;s:4:"mail";i:5;b:1;}',
            $serialized
        );
    }

    /**
     * Should unserialize user.
     */
    public function testUnserialize(): void
    {
        $user = new CMSUser();
        $user->unserialize('a:6:{i:0;i:1;i:1;s:4:"uuid";i:2;s:8:"username";i:3;s:8:"password";i:4;s:4:"mail";i:5;b:1;}');

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('uuid', $user->getUuid());
        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('password', $user->getPassword());
        $this->assertEquals('mail', $user->getEmail());
        $this->assertTrue($user->isEnabled());
    }

    /**
     * Should return username.
     */
    public function testToString(): void
    {
        $user = new CMSUser();
        $user->setUsername('username');

        $this->assertEquals('username', $user);
    }
}
