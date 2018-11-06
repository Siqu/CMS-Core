<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AcceptLanguageListener
 * @package Siqu\CMS\API\EventListener
 */
class AcceptLanguageListener extends APIAttributeListener
{
    /**
     * Only listen for accept-language listener.
     *
     * @return string
     */
    protected function getListenerName(): string
    {
        return ListenerAttributes::ACCEPT_LANGUAGE_LISTENER;
    }

    /**
     * Set locale from preferred languages of request.
     *
     * @param Request $request
     */
    protected function handleKernelRequest(Request $request): void
    {
        $preferredLocale = $request->getPreferredLanguage(['en', 'de']);

        $request->setLocale($preferredLocale);
    }
}
