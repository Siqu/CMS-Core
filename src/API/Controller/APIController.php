<?php

namespace Siqu\CMS\API\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class APIController
 * @package Siqu\CMS\API\Controller
 */
abstract class APIController extends Controller
{
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

        if(!$entry) {
            return $this->createResponse([
                'message' => 'No entry found with uuid ' . $uuid
            ], Response::HTTP_NOT_FOUND, $request);
        }

        return $this->createResponse($entry, Response::HTTP_OK, $request);
    }

    /**
     * Create a new entry.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $class = $this->getEntityClass();
        $entry = new $class();

        $form = $this->createForm($this->getFormType(), $entry);

        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()) {
            $entry = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entry);
            $manager->flush();

            return $this->createResponse($entry, Response::HTTP_CREATED, $request);
        }

        return $this->createResponse($form->getErrors(true), Response::HTTP_BAD_REQUEST, $request);
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

        if(!$entry) {
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
     * Update a entry.
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     */
    public function update(string $uuid, Request $request): Response
    {
        $entry = $this->loadEntry($uuid);

        if(!$entry) {
            return $this->createResponse([
                'message' => 'No entry found with uuid ' . $uuid
            ], Response::HTTP_NOT_FOUND, $request);
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm($this->getFormType(), $entry);

        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()) {
            $entry = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entry);
            $manager->flush();

            return $this->createResponse($entry, Response::HTTP_CREATED, $request);
        }

        return $this->createResponse($form->getErrors(true), Response::HTTP_BAD_REQUEST, $request);
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

        if($data instanceof FormErrorIterator) {
            $errors = [];
            foreach($data as $error) {
                /** @var ConstraintViolation $cause */
                $cause = $error->getCause();
                $errors[] = [
                    'message' => $error->getMessage(),
                    'path' => $cause->getPropertyPath(),
                    'data' => $cause->getInvalidValue()
                ];
            }
            $serialized = $serializer->serialize($errors, $request->getFormat($request->getRequestFormat()));
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
    protected function getRepository(): ObjectRepository {
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
     * Retrieve the class of the managed entity.
     *
     * @return string
     */
    abstract protected function getEntityClass(): string;

    /**
     * Retrieve the class of the form of the managed entity.
     *
     * @return string
     */
    abstract protected function getFormType(): string;
}