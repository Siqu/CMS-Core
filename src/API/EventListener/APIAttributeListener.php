<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class APIAttributeListener
 * @package Siqu\CMS\API\EventListener
 */
abstract class APIAttributeListener
{
    /**
     * Retrieve the name of the listener function.
     * @return string
     * @see ListenerAttributes::ACCEPT_LANGUAGE_LISTENER
     * @see ListenerAttributes::ACCEPT_LISTENER
     * @see ListenerAttributes::API_EXCEPTION_LISTENER
     * @see ListenerAttributes::RESPONSE_FORMATTER_LISTENER
     */
    abstract protected function getListenerName(): string;

    /**
     * Handle a kernel exception.
     * Check if the needed listener is active.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isListenerActive($request)) {
            return;
        }

        $this->handleKernelException($event);
    }

    /**
     * Handle a kernel Request.
     * Check if the needed listener is active.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isListenerActive($request)) {
            return;
        }

        $this->handleKernelRequest($request);
    }

    /**
     * Handle a kernel response..
     * Check if the needed listener is active.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isListenerActive($request)) {
            return;
        }

        $this->handleKernelResponse($request, $event->getResponse());
    }

    /**
     * Handle the given Kernel exception.
     * @param GetResponseForExceptionEvent $event
     * @codeCoverageIgnore
     */
    protected function handleKernelException(GetResponseForExceptionEvent $event): void
    {
        // nope
    }

    /**
     * Handle the given Kernel Request.
     * @param Request $request
     * @codeCoverageIgnore
     */
    protected function handleKernelRequest(Request $request): void
    {
        // nope
    }

    /**
     * Handle the given Kernel response.
     * @param Request $request
     * @param Response $response
     * @codeCoverageIgnore
     */
    protected function handleKernelResponse(Request $request, Response $response): void
    {
        // nope
    }

    /**
     * Check if the given Listener is active.
     *
     * @param Request $request
     * @return bool
     */
    protected function isListenerActive(Request $request): bool
    {
        /** @var ListenerAttributes $listener */
        $listener = $request->attributes->get('listener');

        return $listener->isListenerActive($this->getListenerName());
    }
}
