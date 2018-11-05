<?php

namespace Siqu\CMS\API\Normalizer;

/**
 * Class CircularReferenceHandlerInterface
 * @package Siqu\CMS\API\Normalizer
 */
interface CircularReferenceHandlerInterface
{
    /**
     * Handle a object for circular reference
     *
     * @param mixed $data
     * @return mixed
     * @throws \ReflectionException
     */
    public function handle($data);
}
