<?php

namespace Siqu\CMS\Core\Tests\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use Siqu\CMS\Core\Doctrine\Listener\PageListener;
use Siqu\CMS\Core\Entity\Page;
use Siqu\CMS\Core\Util\Urlizer;

/**
 * Class PageListenerTest
 * @package Siqu\CMS\Core\Tests\Doctrine\Listener
 */
class PageListenerTest extends AbstractBaseListenerTest
{
    /** @var PageListener */
    private $listener;

    /** @var Urlizer|MockObject */
    private $urlizer;

    /**
     * Should create proper object
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            PageListener::class,
            $this->listener
        );
    }

    /**
     * Test persist with correct object
     */
    public function testPrePersist(): void
    {
        $object = new Page();
        $object->setTitle('title');
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);

        $this->assertEquals(
            'title',
            $object->getSlug()
        );
    }

    /**
     * Test persist with incorrect object
     */
    public function testPrePersistIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setSlug'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setSlug');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);
    }

    /**
     * Test persist with correct object and parent
     */
    public function testPrePersistWithParent(): void
    {
        $object = new Page();
        $object->setTitle('title');
        $parent = new Page();
        $parent->setSlug('parent');
        $object->setParent($parent);
        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->prePersist($args);

        $this->assertEquals(
            'parent/title',
            $object->getSlug()
        );
    }

    /**
     * Test should change slug
     */
    public function testPreUpdate(): void
    {
        $object = new Page();
        $object->setTitle('title');

        $args = new LifecycleEventArgs(
            $object,
            $this->entityManager
        );

        $this->listener->preUpdate($args);

        $this->assertEquals(
            'title',
            $object->getSlug()
        );
    }

    /**
     * Test should not change slug
     */
    public function testPreUpdateIncorrectObject(): void
    {
        $object = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['setSlug'])
            ->getMock();
        $object
            ->expects($this->never())
            ->method('setSlug');

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

        $this->urlizer = $this->getMockBuilder(Urlizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlizer
            ->method('generateSlug')
            ->willReturnCallback(
                function ($slug) {
                    return $slug;
                }
            );

        $this->listener = new PageListener($this->urlizer);
    }
}
