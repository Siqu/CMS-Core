<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use Siqu\CMS\Core\Doctrine\Listener\IdentifiableListener;
use Siqu\CMS\Core\Tests\Dummy\IdentifiableObject;
use Siqu\CMS\Core\Util\UuidGenerator;

/**
 * Class IdentifiableListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
class IdentifiableListenerTest extends AbstractBaseListenerTest
{
    /** @var IdentifiableListener */
    private $listener;

    /** @var UuidGenerator|MockObject */
    private $uuidGenerator;

    /**
     * Should create proper object
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            IdentifiableListener::class,
            $this->listener
        );
    }

    /**
     * Test persist with correct object
     */
    public function testPrePersist(): void
    {
        $object = new IdentifiableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);

        $this->assertEquals(
            'uuid',
            $object->getUuid()
        );
    }

    /**
     * Test persist with incorrect object
     */
    public function testPrePersistIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setUuid'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setUuid');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);
    }

    /**
     * Test should not change uuid
     */
    public function testPreUpdate(): void
    {
        $object = $this->getMockBuilder(IdentifiableObject::class)
            ->disableOriginalConstructor()
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setUuid');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);
    }

    /**
     * Test should not change uuid
     */
    public function testPreUpdateIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setUuid'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setUuid');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->uuidGenerator
            ->method('generate')
            ->willReturn('uuid');

        $this->listener = new IdentifiableListener($this->uuidGenerator);
    }
}
