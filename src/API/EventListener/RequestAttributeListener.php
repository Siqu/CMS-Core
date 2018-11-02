<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestAttributeListener
 * @package Siqu\CMS\API\EventListener
 */
class RequestAttributeListener
{
    /**
     * Extract request attributes.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        $listener = $request->attributes->get('listener');
        if (!$listener || !array_key_exists('request', $listener)) {
            $request->attributes->set('listener', new ListenerAttributes());
            return;
        }

        $request->attributes->set('listener', new ListenerAttributes($listener['request']));
    }
}