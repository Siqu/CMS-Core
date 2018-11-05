<?php

namespace Siqu\CMS\API\EventListener;

use Siqu\CMS\API\Exception\APIValidationException;
use Siqu\CMS\API\Http\APIResponse;
use Siqu\CMS\API\Request\ListenerAttributes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class APIExceptionListener
 * @package Siqu\CMS\API\EventListener
 */
class APIExceptionListener
{
    /**
     * Handle API Exceptions.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $request = $event->getRequest();

        /** @var ListenerAttributes $listener */
        $listener = $request->attributes->get('listener');
        if (!$listener->isAPIExceptionActive()) {
            return;
        }

        $exception = $event->getException();

        $class = get_class($exception);
        if ($class === APIValidationException::class) {
            /** @var APIValidationException $exception */
            $response = new APIResponse(
                $exception->getViolations(),
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($response);
        } else {
            if ($class === NotFoundHttpException::class) {
                $response = new APIResponse(
                    [
                        'message' => 'No Entry for found.'
                    ],
                    Response::HTTP_NOT_FOUND
                );

                $event->setResponse($response);
            }
        }
    }
}
