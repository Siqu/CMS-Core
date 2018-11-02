<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Http\APIResponse;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class APIResponseListener
 * @package Siqu\CMS\API\EventListener
 */
class APIResponseListener
{
    /** @var SerializerInterface */
    private $serializer;

    /**
     * APIResponseListener constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    )
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize data for a api response.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        /** @var ListenerAttributes $listener */
        $listener = $request->attributes->get('listener');
        if (
            !$listener->isResponseFormatterActive() ||
            get_class($response) !== APIResponse::class
        ) {
            return;
        }

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
        $response->headers->set('Content-Type', $request->getRequestFormat());

    }
}