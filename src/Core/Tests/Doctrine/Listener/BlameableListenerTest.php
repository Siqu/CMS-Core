<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use Siqu\CMS\Core\Doctrine\Listener\BlameableListener;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Tests\Dummy\BlameableObject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class BlameableListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
class BlameableListenerTest extends AbstractBaseListenerTest
{
    /** @var BlameableListener */
    private $listener;
    /** @var TokenInterface|MockObject */
    private $token;
    /** @var TokenStorage|MockObject */
    private $tokenStorage;
    /** @var CMSUser */
    private $user;

    /**
     * Should create proper object
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            BlameableListener::class,
            $this->listener
        );
    }

    /**
     * Test persist with correct object
     */
    public function testPrePersist(): void
    {
        $object = new BlameableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);

        $this->assertNull($object->getChangeUser());

        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->tokenStorage
            ->method('getToken')
            ->willReturn($this->token);

        $this->listener->prePersist($args);

        $this->assertEquals(
            $this->user,
            $object->getUser()
        );
    }

    /**
     * Test persist with incorrect object
     */
    public function testPrePersistIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setUser'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setUser');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);
    }

    /**
     * Test persist with correct object but without user
     */
    public function testPrePersistWithoutUser(): void
    {
        $object = new BlameableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);

        $this->assertNull($object->getUser());

        $this->token
            ->method('getUser')
            ->willReturn('');

        $this->tokenStorage
            ->method('getToken')
            ->willReturn($this->token);

        $this->listener->prePersist($args);

        $this->assertNull($object->getChangeUser());
    }

    /**
     * Test update with correct object.
     */
    public function testPreUpdate(): void
    {
        $object = new BlameableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);

        $this->assertNull($object->getChangeUser());

        $this->token
            ->method('getUser')
            ->willReturn($this->user);

        $this->tokenStorage
            ->method('getToken')
            ->willReturn($this->token);

        $this->listener->preUpdate($args);

        $this->assertEquals(
            $this->user,
            $object->getChangeUser()
        );
    }

    /**
     * Test update with incorrect objects
     */
    public function testPreUpdateIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setUser'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setUser');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);
    }

    /**
     * Test update with correct object but without user.
     */
    public function testPreUpdateWithoutUser(): void
    {
        $object = new BlameableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);

        $this->assertNull($object->getChangeUser());

        $this->token
            ->method('getUser')
            ->willReturn('');

        $this->tokenStorage
            ->method('getToken')
            ->willReturn($this->token);

        $this->listener->preUpdate($args);

        $this->assertNull($object->getChangeUser());
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new CMSUser();

        $this->token = $this->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenStorage = $this->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new BlameableListener($this->tokenStorage);
    }
}
