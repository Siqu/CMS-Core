<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use Siqu\CMS\Core\Doctrine\Listener\CMSUserListener;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Util\PasswordUpdater;
use Siqu\CMS\Core\Util\UuidGenerator;

/**
 * Class CMSUserListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
class CMSUserListenerTest extends AbstractBaseListenerTest
{
    /** @var CMSUserListener */
    private $listener;

    /** @var PasswordUpdater|MockObject */
    private $passwordUpdater;

    /** @var UuidGenerator|MockObject */
    private $uuidGenerator;

    /**
     * Should construct proper object.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(CMSUserListener::class, $this->listener);
    }

    /**
     * Should call password updater
     */
    public function testPrePersist(): void
    {
        $object = new CMSUser();

        $this->passwordUpdater
            ->expects($this->once())
            ->method('hashPassword')
            ->with($object);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('1');

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->prePersist($event);
    }

    /**
     * Should not call password updater
     */
    public function testPrePersistIncorrectObject(): void
    {
        $this->passwordUpdater
            ->expects($this->never())
            ->method('hashPassword');

        $this->uuidGenerator
            ->expects($this->never())
            ->method('generate');

        $object = new \stdClass();

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->prePersist($event);
    }

    /**
     * Should call password updater and entity manager
     */
    public function testPreUpdate(): void
    {
        $object = new CMSUser();

        $this->passwordUpdater
            ->expects($this->once())
            ->method('hashPassword')
            ->with($object);

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->preUpdate($event);
    }

    /**
     * Should not call password updater or entity manager
     */
    public function testPreUpdateIncorrectObject(): void
    {
        $this->passwordUpdater
            ->expects($this->never())
            ->method('hashPassword');

        $object = new \stdClass();

        $event = new LifecycleEventArgs($object, $this->entityManager);

        $this->listener->preUpdate($event);
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordUpdater = $this->getMockBuilder(PasswordUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new CMSUserListener($this->passwordUpdater, $this->uuidGenerator);
    }
}
