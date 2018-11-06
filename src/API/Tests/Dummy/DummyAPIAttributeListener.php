<?php

namespace Siqu\CMS\API\Tests\Dummy;

use Siqu\CMS\API\EventListener\APIAttributeListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class DummyAPIAttributeListener
 * @package Siqu\CMS\API\Tests\Dummy
 */
class DummyAPIAttributeListener extends APIAttributeListener
{

    /**
     * Retrieve the name of the listener function.
     * @return string
     * @see ListenerAttributes::ACCEPT_LANGUAGE_LISTENER
     * @see ListenerAttributes::ACCEPT_LISTENER
     * @see ListenerAttributes::API_EXCEPTION_LISTENER
     * @see ListenerAttributes::RESPONSE_FORMATTER_LISTENER
     */
    protected function getListenerName(): string
    {
        return 'dummy';
    }

    protected function handleKernelException(GetResponseForExceptionEvent $event): void
    {
        $event->getResponse();
    }

    protected function handleKernelRequest(Request $request): void
    {
        $request->setLocale('en');
    }

    protected function handleKernelResponse(Request $request, Response $response): void
    {
        $request->setLocale('en');
    }
}
