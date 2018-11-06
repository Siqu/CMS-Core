<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Http\APIResponse;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class APIResponseListener
 * @package Siqu\CMS\API\EventListener
 */
class APIResponseListener extends APIAttributeListener
{
    /** @var SerializerInterface */
    private $serializer;

    /**
     * APIResponseListener constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    /**
     * Only listener for response formatter.
     * @return string
     */
    protected function getListenerName(): string
    {
        return ListenerAttributes::RESPONSE_FORMATTER_LISTENER;
    }

    /**
     * Set response content depending on the request format.
     *
     * @param Request $request
     * @param Response $response
     */
    protected function handleKernelResponse(Request $request, Response $response): void
    {
        /** @var APIResponse $response */
        $data = $response->getData();

        if ($data instanceof ConstraintViolationListInterface) {
            $errors = [];
            foreach ($data as $error) {
                $errors[] = [
                    'message' => $error->getMessage(),
                    'path' => $error->getPropertyPath(),
                    'data' => $error->getInvalidValue()
                ];
            }
            $serialized = $this->serializer->serialize(
                $errors,
                $request->getFormat($request->getRequestFormat())
            );
        } else {
            $serialized = $this->serializer->serialize(
                $data,
                $request->getFormat($request->getRequestFormat()),
                ['groups' => ['api']]
            );
        }

        $response->setContent($serialized);
        $response->headers->set(
            'Content-Type',
            $request->getRequestFormat()
        );
    }
}
