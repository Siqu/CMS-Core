<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AcceptListener
 * @package Siqu\CMS\API\EventListener
 */
class AcceptListener extends APIAttributeListener
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
     * Only listen for accept attributes.
     * @return string
     */
    protected function getListenerName(): string
    {
        return ListenerAttributes::ACCEPT_LISTENER;
    }

    /**
     * Set request format depending on acceptable content type.
     * @param Request $request
     */
    protected function handleKernelRequest(Request $request): void
    {
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
