<?php

namespace Siqu\CMS\API\EventListener;

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

        $listener = $request->attributes->get('listener');
        if (
            !$listener ||
            !array_key_exists('request', $listener) ||
            !array_key_exists('accept-language', $listener['request'])
        ) {
            return;
        }
        $preferredLocale = $request->getPreferredLanguage(['de', 'en']);

        $request->setLocale($preferredLocale);
    }
}