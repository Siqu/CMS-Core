<?php

namespace Siqu\CMS\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class APIController
 * @package Siqu\CMS\API\Controller
 */
class APIController extends Controller
{
    /**
     * Create a proper api response for the given data.
     *
     * @param mixed $data
     * @param Request $request
     * @return Response
     */
    protected function createResponse($data, Request $request): Response
    {
        $serializer = $this->get('serializer');
        $serialized = $serializer->serialize($data, $request->getFormat($request->getRequestFormat()));

        $response = new Response($serialized, Response::HTTP_OK, [
            'Content-Type' => $request->getRequestFormat()
        ]);

        return $response;
    }
}