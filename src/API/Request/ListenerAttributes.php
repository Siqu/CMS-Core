<?php

namespace Siqu\CMS\API\Request;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class ListenerAttributes
 * @package Siqu\CMS\API\Request
 */
class ListenerAttributes
{
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
     * Check if api exception listener is active.
     *
     * @return bool
     */
    public function isAPIExceptionActive(): bool
    {
        return $this->attributes->get(
            'api-exception',
            false
        );
    }

    /**
     * Check if accept attribute is active.
     *
     * @return bool
     */
    public function isAcceptActive(): bool
    {
        return $this->attributes->get(
            'accept',
            false
        );
    }

    /**
     * Check if accept language attribute is active.
     *
     * @return bool
     */
    public function isAcceptLanguageActive(): bool
    {
        return $this->attributes->get(
            'accept-language',
            false
        );
    }

    /**
     * Check if the response formatter is active.
     *
     * @return bool
     */
    public function isResponseFormatterActive(): bool
    {
        return $this->attributes->get(
            'response-formatter',
            false
        );
    }
}
