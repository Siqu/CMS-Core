<?php

namespace Siqu\CMS\API\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class APIResponse
 * @package Siqu\CMS\API\Http
 */
class APIResponse extends Response
{
    /** @var mixed */
    private $data;

    /**
     * APIResponse constructor.
     * @param $data
     * @param int $status
     */
    public function __construct($data, int $status = Response::HTTP_OK)
    {
        parent::__construct('', $status);

        $this->data = $data;
    }

    /**
     * Retrieve the data of the response.
     *
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }
}