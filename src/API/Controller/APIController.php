<?php

namespace Siqu\CMS\API\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Siqu\CMS\API\Exception\APIValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class APIController
 * @package Siqu\CMS\API\Controller
 */
abstract class APIController extends Controller
{
    /**
     * Retrieve the class of the managed entity.
     *
     * @return string
     */
    abstract protected function getEntityClass(): string;

    /**
     * Create a new entry.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        try {
            $entry = $this->deserializeData($request, 'new');

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entry);
            $manager->flush();

            return $this->createResponse($entry, Response::HTTP_CREATED, $request);
        } catch (APIValidationException $e) {
            return $this->createResponse($e->getViolations(), Response::HTTP_BAD_REQUEST, $request);
        }
    }

    /**
     * Delete a entry.
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     */
    public function delete(string $uuid, Request $request): Response
    {
        $entry = $this->loadEntry($uuid);

        if (!$entry) {
            return $this->createResponse([
                'message' => 'No entry found with uuid ' . $uuid
            ], Response::HTTP_NOT_FOUND, $request);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entry);
        $manager->flush();

        return $this->createResponse([
            'message' => 'Delete success.'
        ], Response::HTTP_NO_CONTENT, $request);
    }

    /**
     * Read all entries.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $entries = $this->getRepository()->findAll();

        return $this->createResponse($entries, Response::HTTP_OK, $request);
    }

    /**
     * Read a specific user
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     */
    public function show(string $uuid, Request $request): Response
    {
        $entry = $this->loadEntry($uuid);

        if (!$entry) {
            return $this->createResponse([
                'message' => 'No entry found with uuid ' . $uuid
            ], Response::HTTP_NOT_FOUND, $request);
        }

        return $this->createResponse($entry, Response::HTTP_OK, $request);
    }

    /**
     * Update a entry.
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     */
    public function update(string $uuid, Request $request): Response
    {
        $entry = $this->loadEntry($uuid);

        if (!$entry) {
            return $this->createResponse([
                'message' => 'No entry found with uuid ' . $uuid
            ], Response::HTTP_NOT_FOUND, $request);
        }

        try {
            $entry = $this->deserializeData($request, 'update', $entry);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entry);
            $manager->flush();

            return $this->createResponse($entry, Response::HTTP_OK, $request);
        } catch (APIValidationException $e) {
            return $this->createResponse($e->getViolations(), Response::HTTP_BAD_REQUEST, $request);
        }
    }

    /**
     * Create a proper api response for the given data.
     *
     * @param mixed $data
     * @param int $statusCode
     * @param Request $request
     * @return Response
     */
    protected function createResponse($data, int $statusCode, Request $request): Response
    {
        $serializer = $this->get('serializer');

        if ($data instanceof ConstraintViolationListInterface) {
            $errors = [];
            foreach ($data as $error) {
                $errors[] = [
                    'message' => $error->getMessage(),
                    'path' => $error->getPropertyPath(),
                    'data' => $error->getInvalidValue()
                ];
            }
            $serialized = $serializer->serialize($errors, $this->getSerializerFormat($request));
        } else {
            $serialized = $serializer->serialize(
                $data,
                $request->getFormat($request->getRequestFormat()),
                ['groups' => ['api']]
            );
        }

        $response = new Response($serialized, $statusCode, [
            'Content-Type' => $request->getRequestFormat()
        ]);

        return $response;
    }

    /**
     * Retrieve the repository.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->getDoctrine()->getRepository($this->getEntityClass());
    }

    /**
     * Read a entry by its' uuid.
     *
     * @param string $uuid
     * @return null|object
     */
    protected function loadEntry(string $uuid): ?object
    {
        $entry = $this->getRepository()->findOneBy([
            'uuid' => $uuid
        ]);

        return $entry;
    }

    /**
     * Deserialize the request data into a object.
     *
     * @param Request $request
     * @param string $validationGroup
     * @param null|object $entry
     * @return object
     * @throws APIValidationException
     */
    private function deserializeData(Request $request, string $validationGroup, $entry = null): object
    {
        $serializer = $this->get('serializer');
        $validator = $this->get('validator');

        if (!$entry) {
            $class = $this->getEntityClass();
            $entry = new $class();
        }

        $entry = $serializer->deserialize($request->getContent(), $this->getEntityClass(), $this->getSerializerFormat($request), [
            'object_to_populate' => $entry
        ]);

        $errors = $validator->validate($entry, null, $validationGroup);

        if (count($errors) > 0) {
            throw new APIValidationException($errors);
        }

        return $entry;
    }

    /**
     * Retrieve the format for the serializer.
     *
     * @param Request $request
     * @return null|string
     */
    private function getSerializerFormat(Request $request): ?string
    {
        return $request->getFormat($request->getRequestFormat());
    }
}