<?php

namespace Siqu\CMS\API\Serializer;

use Symfony\Component\Serializer\Serializer;

/**
 * Class APISerializer
 * @package Siqu\CMS\API\Serializer
 */
class APISerializer extends Serializer
{
    public function __construct(array $normalizers = array(), array $encoders = array())
    {
        parent::__construct($normalizers, $encoders);
    }
}