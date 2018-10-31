<?php

namespace Siqu\CMS\Core\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Entity\Group;
use Siqu\CMS\Core\Entity\IdentifiableTrait;

/**
 * Class CMSUserTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class CMSUserTest extends TestCase
{
    /** @var CMSUser */
    private $object;

    /**
     * Should add, get and remove groups.
     */
    public function testAddGetSetRemoveGroups(): void
    {
        $group = new Group();
        $group->setName('name');

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
     * Should create instance
     * @throws \ReflectionException
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(CMSUser::class, $this->object);
        $this->assertTrue($this->object->isEnabled());
        $this->assertEquals([CMSUser::ROLE_DEFAULT], $this->object->getRoles());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getGroups());
        $this->assertEmpty($this->object->getGroups());

        $reflection = new \ReflectionClass($this->object);
        $traits = $reflection->getTraitNames();

        $this->assertEquals([
            IdentifiableTrait::class
        ], $traits);
    }

    /**
     * Should return group names
     */
    public function testGetGroupNames(): void
    {
        $group1 = new Group();
        $group1->setName('name1');
        $group2 = new Group();
        $group2->setName('name2');
        $this->object->addGroup($group1);
        $this->object->addGroup($group2);

        $this->assertEquals([
            'name1',
            'name2'
        ], $this->object->getGroupNames());
    }

    /**
     * Should merge roles and groups.
     */
    public function testGetRoles(): void
    {
        $group = new Group();
        $group->setName('name');
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
     * Should return null.
     */
    public function testGetSalt(): void
    {
        $this->assertNull($this->object->getSalt());
    }

    /**
     * Should check if group exists
     */
    public function testHasGroup(): void
    {
        $group = new Group();
        $group->setName('name');
        $this->object->addGroup($group);

        $this->assertTrue($this->object->hasGroup('name'));
        $this->assertFalse($this->object->hasGroup('name2'));
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

        $serialized = $user->serialize();

        $this->assertEquals(
            'a:6:{i:0;i:1;i:1;s:4:"uuid";i:2;s:8:"username";i:3;s:8:"password";i:4;s:4:"mail";i:5;b:1;}',
            $serialized
        );
    }

    /**
     * Should set and get account non expired
     */
    public function testSetGetAccountNonExpired(): void
    {
        $this->assertTrue($this->object->isAccountNonExpired());
        $this->object->setAccountNonExpired(false);
        $this->assertFalse($this->object->isAccountNonExpired());
    }

    /**
     * Should set and get account non locked
     */
    public function testSetGetAccountNonLocked(): void
    {
        $this->assertTrue($this->object->isAccountNonLocked());
        $this->object->setAccountNonLocked(false);
        $this->assertFalse($this->object->isAccountNonLocked());
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
     * Should set and get credentials non expired
     */
    public function testSetGetCredentialsNonExpired(): void
    {
        $this->assertTrue($this->object->isCredentialsNonExpired());
        $this->object->setCredentialsNonExpired(false);
        $this->assertFalse($this->object->isCredentialsNonExpired());
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
        $this->assertTrue($this->object->isEnabled());
        $this->object->setEnabled(false);
        $this->assertFalse($this->object->isEnabled());
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
     * Should set and get username.
     */
    public function testSetGetUsername(): void
    {
        $this->assertNull($this->object->getUsername());
        $this->object->setUsername('username');
        $this->assertEquals('username', $this->object->getUsername());
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
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new CMSUser();
    }
}
