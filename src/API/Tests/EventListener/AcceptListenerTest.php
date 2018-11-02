<?php

namespace Siqu\CMS\API\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\EventListener\AcceptListener;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AcceptListenerTest
 * @package Siqu\CMS\API\Tests\EventListener
 */
class AcceptListenerTest extends TestCase
{
    /** @var Kernel|MockObject */
    private $kernel;

    /** @var AcceptListener */
    private $listener;

    /**
     * Should create correct instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AcceptListener::class, $this->listener);
    }

    /**
     * Should change request format.
     */
    public function testOnKernelRequest(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/json');
        $request->attributes->set('listener', new ListenerAttributes([
            'accept' => true
        ]));
        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('application/json', $request->getRequestFormat());
    }

    /**
     * Should change request format to application/json
     */
    public function testOnKernelRequestUnknownFormat(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/javascript');
        $request->attributes->set('listener', new ListenerAttributes([
            'accept' => true
        ]));
        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('application/json', $request->getRequestFormat());
    }

    /**
     * Should not change request format.
     */
    public function testOnKernelRequestWithoutListener(): void
    {
        $request = new Request();
        $request->attributes->set('listener', new ListenerAttributes());
        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('html', $request->getRequestFormat());
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

        $this->listener = new AcceptListener();
    }
}
