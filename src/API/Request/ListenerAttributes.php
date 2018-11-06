<?php

namespace Siqu\CMS\API\Request;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class ListenerAttributes
 * @package Siqu\CMS\API\Request
 */
class ListenerAttributes
{
    const ACCEPT_LANGUAGE_LISTENER = 'accept-language';
    const ACCEPT_LISTENER = 'accept';
    const API_EXCEPTION_LISTENER = 'api-exception';
    const RESPONSE_FORMATTER_LISTENER = 'response-formatter';

    /** @var ParameterBag */
    private $attributes;

    /**
     * ListenerAttributes constructor.
     * @param array $attributes
     */
    public function __construct(
        array $attributes = []
    ) {
        $this->attributes = new ParameterBag($attributes);
    }

    /**
     * Check if the given listener is active.
     *
     * @param string $listener
     * @return bool
     */
    public function isListenerActive(string $listener): bool
    {
        return $this->attributes->get(
            $listener,
            false
        );
    }
}
