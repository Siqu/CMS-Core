<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Doctrine\Listener\TimestampableListener;
use Siqu\CMS\Core\Tests\Dummy\TimestampableObject;

/**
 * Class TimestampableListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
class TimestampableListenerTest extends AbstractBaseListenerTest
{
    /** @var TimestampableListener */
    private $listener;

    /**
     * Should create proper object
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            TimestampableListener::class,
            $this->listener
        );
    }

    /**
     * Test persist with correct object
     */
    public function testPrePersist(): void
    {
        $object = new TimestampableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);

        $this->assertNotNull($object->getCreatedAt());
    }

    /**
     * Test persist with incorrect object
     */
    public function testPrePersistIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCreatedAt'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setCreatedAt');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);
    }

    /**
     * Test update with correct object.
     */
    public function testPreUpdate(): void
    {
        $object = new TimestampableObject();
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);

        $this->assertNotNull($object->getUpdatedAt());
    }

    /**
     * Test update with incorrect objects
     */
    public function testPreUpdateIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setUpdatedAt'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setUpdatedAt');

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

        $this->listener = new TimestampableListener();
    }
}
