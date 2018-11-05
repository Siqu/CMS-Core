<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractBaseListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
abstract class AbstractBaseListenerTest extends TestCase
{
    /** @var EntityManager|MockObject */
    protected $entityManager;

    /** @var UnitOfWork|MockObject */
    protected $unitOfWork;

    /**
     * Should create proper object
     */
    abstract public function testConstruct(): void;

    /**
     * Test persist with correct object
     */
    abstract public function testPrePersist(): void;

    /**
     * Test persist with incorrect object
     */
    abstract public function testPrePersistIncorrectObject(): void;

    /**
     * Test update with correct object.
     */
    abstract public function testPreUpdate(): void;

    /**
     * Test update with incorrect objects
     */
    abstract public function testPreUpdateIncorrectObject(): void;

    /**
     * Setup methods
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->unitOfWork = $this->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager->method('getUnitOfWork')
            ->willReturn($this->unitOfWork);

        $meta = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager
            ->method('getClassMetadata')
            ->willReturn($meta);
        $this->unitOfWork
            ->method('recomputeSingleEntityChangeSet');
    }
}
