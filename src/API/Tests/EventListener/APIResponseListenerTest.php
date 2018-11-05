<?php

namespace Siqu\CMS\API\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\EventListener\APIResponseListener;
use Siqu\CMS\API\Http\APIResponse;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class APIResponseListenerTest
 * @package Siqu\CMS\API\Tests\EventListener
 */
class APIResponseListenerTest extends TestCase
{
    /** @var KernelInterface|MockObject */
    private $kernel;
    /** @var APIResponseListener */
    private $listener;
    /** @var ListenerAttributes|MockObject */
    private $listenerAttributes;
    /** @var SerializerInterface|MockObject */
    private $serializer;

    /**
     * Should create proper instance.
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(
            APIResponseListener::class,
            $this->listener
        );
    }

    /**
     * Should return data
     */
    public function testOnKernelResponse(): void
    {
        $this->listenerAttributes
            ->method('isResponseFormatterActive')
            ->willReturn(true);

        $response = new APIResponse(
            []
        );
        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );
        $request->setRequestFormat('application/json');

        $this->serializer
            ->method('serialize')
            ->with(
                [],
                'json'
            )
            ->willReturn('serialized');

        $event = new FilterResponseEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            $response
        );

        $this->listener->onKernelResponse($event);

        $this->assertEquals(
            'serialized',
            $response->getContent()
        );
        $this->assertEquals(
            'application/json',
            $response->headers->get('Content-Type')
        );
    }

    /**
     * Should not change response.
     */
    public function testOnKernelResponseIncorrectResponse(): void
    {
        $this->listenerAttributes
            ->method('isResponseFormatterActive')
            ->willReturn(true);

        $response = new Response();
        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );

        $event = new FilterResponseEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            $response
        );

        $this->listener->onKernelResponse($event);

        $this->assertEquals(
            '',
            $response->getContent()
        );
    }

    /**
     * Should not change response.
     */
    public function testOnKernelResponseNoListener(): void
    {
        $this->listenerAttributes
            ->method('isResponseFormatterActive')
            ->willReturn(false);

        $response = new Response();
        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );

        $event = new FilterResponseEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            $response
        );

        $this->listener->onKernelResponse($event);

        $this->assertEquals(
            '',
            $response->getContent()
        );
    }

    /**
     * Should return errors
     */
    public function testOnKernelResponseWithViolation(): void
    {
        $this->listenerAttributes
            ->method('isResponseFormatterActive')
            ->willReturn(true);

        $violations = new ConstraintViolationList(
            [
                new ConstraintViolation(
                    'message',
                    'template',
                    [],
                    null,
                    'path',
                    null
                )
            ]
        );
        $response = new APIResponse($violations);
        $request = new Request(
            [],
            [],
            [
                'listener' => $this->listenerAttributes
            ]
        );
        $request->setRequestFormat('application/json');

        $this->serializer
            ->method('serialize')
            ->with(
                [
                    [
                        'message' => 'message',
                        'path' => 'path',
                        'data' => null
                    ]
                ],
                'json'
            )
            ->willReturn('serialized');

        $event = new FilterResponseEvent(
            $this->kernel,
            $request,
            KernelInterface::MASTER_REQUEST,
            $response
        );

        $this->listener->onKernelResponse($event);

        $this->assertEquals(
            'serialized',
            $response->getContent()
        );
        $this->assertEquals(
            'application/json',
            $response->headers->get('Content-Type')
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->kernel = $this->getMockBuilder(KernelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listenerAttributes = $this->getMockBuilder(ListenerAttributes::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new APIResponseListener($this->serializer);
    }
}
