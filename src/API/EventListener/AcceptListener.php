<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class AcceptListener
 * @package Siqu\CMS\API\EventListener
 */
class AcceptListener
{
    /** @var array */
    private $contentTypes;

    /**
     * AcceptListener constructor.
     */
    public function __construct()
    {
        $this->contentTypes = [
            'application/json',
            'application/xml'
        ];
    }

    /**
     * Retrieve accept header from request and set content type of request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        /** @var ListenerAttributes $listener */
        $listener = $request->attributes->get('listener');
        if (!$listener->isAcceptActive()) {
            return;
        }
        $acceptableContentTypes = $request->getAcceptableContentTypes();

        foreach ($acceptableContentTypes as $acceptableContentType) {
            if (in_array($acceptableContentType, $this->contentTypes)) {
                $request->setRequestFormat($acceptableContentType);

                return;
            }
        }

        $request->setRequestFormat('application/json');
    }
}
