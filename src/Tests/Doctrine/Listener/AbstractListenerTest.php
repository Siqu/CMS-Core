<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Doctrine\Listener\AbstractListener;
use Siqu\CMS\Core\Tests\Dummy\DummyListener;

/**
 * Class AbstractListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
class AbstractListenerTest extends TestCase
{
    /** @var \stdClass */
    private $object;

    /** @var ClassMetadata|MockObject */
    private $metadata;

    /** @var AbstractListener */
    private $listener;

    /** @var EntityManager|MockObject */
    private $entityManager;

    /** @var UnitOfWork|MockObject */
    private $unitOfWork;

    /**
     * Should return events.
     */
    public function testGetSubscribedEvents(): void
    {
        $this->assertEquals([
            'prePersist',
            'preUpdate'
        ], $this->listener->getSubscribedEvents());
    }

    /**
     * Should recompute change set
     */
    public function testPreUpdate(): void
    {
        $this->mockMethods();
        $args = new LifecycleEventArgs($this->object, $this->entityManager);

        $this->listener->prePersist($args);
    }

    /**
     * Should recompute change set
     */
    public function testPrePersist(): void
    {
        $this->mockMethods();
        $args = new LifecycleEventArgs($this->object, $this->entityManager);

        $this->listener->prePersist($args);
    }

    /**
     * Mock method calls
     */
    private function mockMethods(): void
    {
        $this->unitOfWork
            ->expects($this->once())
            ->method('recomputeSingleEntityChangeSet')
            ->with($this->metadata, $this->object);

        $this->entityManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->willReturn($this->metadata);

        $this->entityManager
            ->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($this->unitOfWork);
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->unitOfWork = $this->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->metadata = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new \stdClass();

        $this->listener = new DummyListener();
    }
}
