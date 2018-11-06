<?php

namespace Siqu\CMS\API\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\EventListener\APIAttributeListener;
use Siqu\CMS\API\Request\ListenerAttributes;
use Siqu\CMS\API\Tests\Dummy\DummyAPIAttributeListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class APIAttributeListenerTest
 * @package Siqu\CMS\API\Tests\EventListener
 */
class APIAttributeListenerTest extends TestCase
{
    /** @var ListenerAttributes|MockObject */
    private $attributes;
    /** @var APIAttributeListener */
    private $listener;
    /** @var Request */
    private $request;
    /** @var Response|MockObject */
    private $response;

    /**
     * Should check if listener is active
     *
     * @APIAttributeListener::onKernelException
     * @APIAttributeListener::handleKernelException
     */
    public function testOnKernelException(): void
    {
        $this->attributes
            ->expects($this->once())
            ->method('isListenerActive')
            ->with('dummy')
            ->willReturn(true);

        /** @var GetResponseForExceptionEvent|MockObject $event */
        $event = $this->getMockBuilder(GetResponseForExceptionEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getRequest')
            ->willReturn($this->request);
        $event
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($this->response);

        $this->listener->onKernelException($event);
    }

    /**
     * Should check if listener is active
     *
     * @APIAttributeListener::onKernelException
     * @APIAttributeListener::handleKernelException
     */
    public function testOnKernelExceptionInactive(): void
    {
        $this->attributes
            ->expects($this->once())
            ->method('isListenerActive')
            ->with('dummy')
            ->willReturn(false);

        /** @var GetResponseForExceptionEvent|MockObject $event */
        $event = $this->getMockBuilder(GetResponseForExceptionEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getRequest')
            ->willReturn($this->request);
        $event
            ->expects($this->never())
            ->method('getResponse')
            ->willReturn($this->response);

        $this->listener->onKernelException($event);
    }

    /**
     * Should check if listener is active
     *
     * @APIAttributeListener::onKernelRequest
     * @APIAttributeListener::handleKernelRequest
     */
    public function testOnKernelRequest(): void
    {
        $this->attributes
            ->expects($this->once())
            ->method('isListenerActive')
            ->with('dummy')
            ->willReturn(true);

        /** @var GetResponseEvent|MockObject $event */
        $event = $this->getMockBuilder(GetResponseEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getRequest')
            ->willReturn($this->request);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('en', $this->request->getLocale());
    }

    /**
     * Should check if listener is active
     *
     * @APIAttributeListener::onKernelRequest
     * @APIAttributeListener::handleKernelRequest
     */
    public function testOnKernelRequestInactive(): void
    {
        $this->attributes
            ->expects($this->once())
            ->method('isListenerActive')
            ->with('dummy')
            ->willReturn(false);

        /** @var GetResponseEvent|MockObject $event */
        $event = $this->getMockBuilder(GetResponseEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getRequest')
            ->willReturn($this->request);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('de', $this->request->getLocale());
    }

    /**
     * Should check if listener is active
     *
     * @APIAttributeListener::onKernelResponse
     * @APIAttributeListener::handleKernelResponse
     */
    public function testOnKernelResponse(): void
    {
        $this->attributes
            ->expects($this->once())
            ->method('isListenerActive')
            ->with('dummy')
            ->willReturn(true);

        /** @var FilterResponseEvent|MockObject $event */
        $event = $this->getMockBuilder(FilterResponseEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getRequest')
            ->willReturn($this->request);
        $event
            ->method('getResponse')
            ->willReturn($this->response);

        $this->listener->onKernelResponse($event);

        $this->assertEquals('en', $this->request->getLocale());
    }

    /**
     * Should check if listener is active
     *
     * @APIAttributeListener::onKernelResponse
     * @APIAttributeListener::handleKernelResponse
     */
    public function testOnKernelResponseInactive(): void
    {
        $this->attributes
            ->expects($this->once())
            ->method('isListenerActive')
            ->with('dummy')
            ->willReturn(false);

        /** @var FilterResponseEvent|MockObject $event */
        $event = $this->getMockBuilder(FilterResponseEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getRequest')
            ->willReturn($this->request);
        $event
            ->method('getResponse')
            ->willReturn($this->response);

        $this->listener->onKernelResponse($event);

        $this->assertEquals('de', $this->request->getLocale());
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = $this->getMockBuilder(ListenerAttributes::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new Request(
            [],
            [],
            [
                'listener' => $this->attributes
            ]
        );
        $this->request->setLocale('de');

        $this->listener = new DummyAPIAttributeListener();
    }
}
