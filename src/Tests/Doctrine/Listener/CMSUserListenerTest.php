<?php

namespace Siqu\CMSCore\Tests\Doctrine\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMSCore\Doctrine\Listener\CMSUserListener;
use Siqu\CMSCore\Entity\CMSUser;
use Siqu\CMSCore\Util\PasswordUpdater;

/**
 * Class CMSUserListenerTest
 * @package Siqu\CMSCore\Tests\Doctrine\Listener
 */
class CMSUserListenerTest extends TestCase
{
    /** @var CMSUserListener */
    private $listener;

    /** @var PasswordUpdater|MockObject */
    private $passwordUpdater;

    /** @var EntityManager|MockObject */
    private $entityManager;

    /** @var UnitOfWork|MockObject */
    private $unitOfWork;

    /**
     * Should construct proper object.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(CMSUserListener::class, $this->listener);
    }

    /**
     * Should not call password updater
     */
    public function testPrePersistIncorrectObject()
    {
        $this->passwordUpdater
            ->expects($this->never())
            ->method('hashPassword');

        $object = new \stdClass();

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->prePersist($event);
    }

    /**
     * Should call password updater
     */
    public function testPrePersist() {
        $object = new CMSUser();

        $this->passwordUpdater
            ->expects($this->once())
            ->method('hashPassword')
            ->with($object);

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->prePersist($event);
    }

    /**
     * Should return correct events.
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals([
            'prePersist',
            'preUpdate'
        ], $this->listener->getSubscribedEvents());
    }

    /**
     * Should not call password updater or entity manager
     */
    public function testPreUpdateIncorrectObject()
    {
        $this->passwordUpdater
            ->expects($this->never())
            ->method('hashPassword');
        $this->entityManager
            ->expects($this->never())
            ->method('getClassMetadata');
        $this->unitOfWork
            ->expects($this->never())
            ->method('recomputeSingleEntityChangeSet');

        $object = new \stdClass();

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->preUpdate($event);
    }

    /**
     * Should call password updater and entity manager
     */
    public function testPreUpdate()
    {
        $object = new CMSUser();

        $meta = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->passwordUpdater
            ->expects($this->once())
            ->method('hashPassword')
            ->with($object);
        $this->entityManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->willReturn($meta);
        $this->unitOfWork
            ->expects($this->once())
            ->method('recomputeSingleEntityChangeSet')
            ->with($meta, $object);

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->preUpdate($event);
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->passwordUpdater = $this->getMockBuilder(PasswordUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->unitOfWork = $this->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager->method('getUnitOfWork')
            ->willReturn($this->unitOfWork);

        $this->listener = new CMSUserListener($this->passwordUpdater);
    }
}
