<?php

namespace Siqu\CMS\API\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\EventListener\AcceptLanguageListener;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AcceptLanguageListenerTest
 * @package Siqu\CMS\API\Tests\EventListener
 */
class AcceptLanguageListenerTest extends TestCase
{
    /** @var AcceptLanguageListener */
    private $listener;

    /** @var Kernel|MockObject */
    private $kernel;

    /**
     * Should create instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AcceptLanguageListener::class, $this->listener);
    }

    /**
     * Should not change locale.
     */
    public function testOnKernelRequestWithoutAcceptLanguageAttribute(): void
    {
        $request = new Request();
        $request->setLocale('de');
        $request->attributes->set('listener', new ListenerAttributes());
        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('de', $request->getLocale());
    }

    /**
     * Should change locale.
     */
    public function testOnKernelRequest(): void
    {
        $request = new Request();
        $request->headers->set('Accept-Language', 'en-EN');
        $request->setLocale('de');
        $request->attributes->set('listener', new ListenerAttributes([
            'accept-language' => true
        ]));
        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('en', $request->getLocale());
    }

    /**
     * Should change locale.
     */
    public function testOnKernelRequestWithUnknownLocale(): void
    {
        $request = new Request();
        $request->headers->set('Accept-Language', 'ru-RU');
        $request->setLocale('de');
        $request->attributes->set('listener', new ListenerAttributes([
            'accept-language' => true
        ]));
        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertEquals('en', $request->getLocale());
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

        $this->listener = new AcceptLanguageListener();
    }
}
