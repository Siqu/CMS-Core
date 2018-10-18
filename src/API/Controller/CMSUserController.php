<?php

namespace Siqu\CMS\API\Controller;

use Siqu\CMS\API\Form\Type\CMSUserType;
use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CMSUserController
 * @package Siqu\CMS\API\Controller
 * @Route("/user", name="api_user")
 */
class CMSUserController extends APIController
{
    /**
     * Read all groups.
     *
     * @param Request $request
     * @return Response
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return parent::index($request);
    }

    /**
     * Read a specific entry
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     * @Route("/{uuid}", name="_show", methods={"GET"})
     */
    public function show(string $uuid, Request $request): Response
    {
        return parent::show($uuid, $request);
    }

    /**
     * Create a new user.
     *
     * @param Request $request
     * @return Response
     * @Route("", name="_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        return parent::create($request);
    }

    /**
     * Update a user.
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     * @Route("/{uuid}", name="_update", methods={"PUT"})
     */
    public function update(string $uuid, Request $request): Response
    {
        return parent::update($uuid, $request);
    }

    /**
     * Delete a user.
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     * @Route("/{uuid}", name="_delete", methods={"DELETE"})
     */
    public function delete(string $uuid, Request $request): Response
    {
        return parent::delete($uuid, $request);
    }

    /**
     * Retrieve the class of the managed entity.
     *
     * @return string
     */
    protected function getEntityClass(): string
    {
        return CMSUser::class;
    }

    /**
     * Retrieve the class of the form of the managed entity.
     *
     * @return string
     */
    protected function getFormType(): string
    {
        return CMSUserType::class;
    }
}