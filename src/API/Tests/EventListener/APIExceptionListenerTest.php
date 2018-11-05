<?php

namespace Siqu\CMS\API\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\EventListener\APIExceptionListener;
use Siqu\CMS\API\Exception\APIValidationException;
use Siqu\CMS\API\Http\APIResponse;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class APIExceptionListenerTest
 * @package Siqu\CMS\API\Tests\EventListener
 */
class APIExceptionListenerTest extends TestCase
{
    /** @var KernelInterface|MockObject */
    private $kernel;
    /** @var APIExceptionListener */
    private $listener;
    /** @var ListenerAttributes|MockObject */
    private $listenerAttributes;

    /**
     * Should create proper instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            APIExceptionListener::class,
            $this->listener
        );
    }

    /**
     * Should not change response.
     */
    public function testOnKernelResponseAPIValidationException(): void
    {
        $this->listenerAttributes
            ->method('isAPIExceptionActive')
            ->willReturn(true);

        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );

        $list = new ConstraintViolationList(
            [
                new ConstraintViolation(
                    'message',
                    'template',
                    [],
                    null,
                    null,
                    null
                )
            ]
        );
        $exception = new APIValidationException($list);

        $event = new GetResponseForExceptionEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            $exception
        );

        $this->listener->onKernelException($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(
            APIResponse::class,
            $response
        );
        /** @var APIResponse $response */
        $this->assertEquals(
            $list,
            $response->getData()
        );
        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
    }

    /**
     * Should not change response.
     */
    public function testOnKernelResponseInvalidException(): void
    {
        $this->listenerAttributes
            ->method('isAPIExceptionActive')
            ->willReturn(true);

        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );

        $event = new GetResponseForExceptionEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            new \Exception()
        );

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * Should not change response.
     */
    public function testOnKernelResponseNoListener(): void
    {
        $this->listenerAttributes
            ->method('isAPIExceptionActive')
            ->willReturn(false);

        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );

        $event = new GetResponseForExceptionEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            new \Exception()
        );

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * Should not change response.
     */
    public function testOnKernelResponseNotFoundHttpException(): void
    {
        $this->listenerAttributes
            ->method('isAPIExceptionActive')
            ->willReturn(true);

        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );
        $exception = new NotFoundHttpException();

        $event = new GetResponseForExceptionEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            $exception
        );

        $this->listener->onKernelException($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(
            APIResponse::class,
            $response
        );
        /** @var APIResponse $response */
        $this->assertEquals(
            [
                'message' => 'No Entry for found.'
            ],
            $response->getData()
        );
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->listenerAttributes = $this->getMockBuilder(ListenerAttributes::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->kernel = $this->getMockBuilder(KernelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new APIExceptionListener();
    }
}
