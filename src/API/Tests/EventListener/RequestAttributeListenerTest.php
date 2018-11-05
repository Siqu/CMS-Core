<?php

namespace Siqu\CMS\API\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\EventListener\RequestAttributeListener;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class RequestAttributeListenerTest
 * @package Siqu\CMS\API\Tests\EventListener
 */
class RequestAttributeListenerTest extends TestCase
{
    /** @var Kernel|MockObject */
    private $kernel;

    /** @var RequestAttributeListener */
    private $listener;

    /**
     * Should create proper instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            RequestAttributeListener::class,
            $this->listener
        );
    }

    /**
     * Should set attributes.
     */
    public function testOnKernelRequestWithRequestListener(): void
    {
        $request = new Request();
        $request->attributes->set(
            'listener',
            [
                'request' => [
                    'test'
                ]
            ]
        );

        $event = new GetResponseEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelRequest($event);

        $attributes = new ListenerAttributes(['test']);
        $this->assertEquals(
            $attributes,
            $request->attributes->get('listener')
        );
    }

    /**
     * Should set empty listener.
     */
    public function testOnKernelRequestWithoutListener(): void
    {
        $request = new Request();

        $event = new GetResponseEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelRequest($event);

        $attributes = new ListenerAttributes();
        $this->assertEquals(
            $attributes,
            $request->attributes->get('listener')
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMockBuilder(Kernel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new RequestAttributeListener();
    }
}
