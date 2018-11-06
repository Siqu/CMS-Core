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
class APIExceptionListener extends APIAttributeListener
{
    /**
     * Only listen for api exceptions
     * @return string
     */
    protected function getListenerName(): string
    {
        return ListenerAttributes::API_EXCEPTION_LISTENER;
    }

    /**
     * Handle API Exceptions.
     *
     * @param GetResponseForExceptionEvent $event
     */
    protected function handleKernelException(GetResponseForExceptionEvent $event): void
    {
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
