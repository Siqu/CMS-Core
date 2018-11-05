<?php

namespace Siqu\CMS\API\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Siqu\CMS\API\Exception\APIValidationException;
use Siqu\CMS\API\Http\APIResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @return Response
     * @throws APIValidationException
     */
    public function create(): Response
    {
        $deserializer = $this->get('siqu.cms_api.deserializer.request');
        $entry = $deserializer->deserializerRequest($this->getEntityClass());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($entry);
        $manager->flush();

        return new APIResponse(
            $entry,
            Response::HTTP_CREATED
        );
    }

    /**
     * Delete a entry.
     *
     * @param string $uuid
     * @return Response
     */
    public function delete(string $uuid): Response
    {
        $entry = $this->loadEntry($uuid);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entry);
        $manager->flush();

        $response = new APIResponse(
            [
                'message' => 'Delete success.'
            ],
            Response::HTTP_NO_CONTENT
        );

        return $response;
    }

    /**
     * Read all entries.
     *
     * @return Response
     */
    public function index(): Response
    {
        $entries = $this->getRepository()->findAll();

        return new APIResponse($entries);
    }

    /**
     * Read a specific user
     *
     * @param string $uuid
     * @return Response
     */
    public function show(string $uuid): Response
    {
        $entry = $this->loadEntry($uuid);

        $response = new APIResponse($entry);

        return $response;
    }

    /**
     * Update a entry.
     *
     * @param string $uuid
     * @return Response
     * @throws APIValidationException
     */
    public function update(string $uuid): Response
    {
        $entry = $this->loadEntry($uuid);

        $deserializer = $this->get('siqu.cms_api.deserializer.request');
        $entry = $deserializer->deserializerRequest(
            $this->getEntityClass(),
            'update',
            $entry
        );

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($entry);
        $manager->flush();

        return new APIResponse(
            $entry,
            Response::HTTP_OK
        );
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
     * @throws NotFoundHttpException
     */
    protected function loadEntry(string $uuid): ?object
    {
        $entry = $this->getRepository()->findOneBy(
            [
                'uuid' => $uuid
            ]
        );

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        return $entry;
    }
}
