<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class AcceptLanguageListener
 * @package Siqu\CMS\API\EventListener
 */
class AcceptLanguageListener
{
    /**
     * Retrieve locale from accept locale header and set current locale.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        /** @var ListenerAttributes $listener */
        $listener = $request->attributes->get('listener');
        if (
        !$listener->isAcceptLanguageActive()
        ) {
            return;
        }
        $preferredLocale = $request->getPreferredLanguage(['en', 'de']);

        $request->setLocale($preferredLocale);
    }
}