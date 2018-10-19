<?php

namespace Siqu\CMS\API\Controller;

use Siqu\CMS\Core\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 * @package Siqu\CMS\API\Controller
 * @Route("/group", name="api_group")
 */
class GroupController extends APIController
{
    /**
     * Create a new group.
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
     * Delete a group.
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
     * Read a specific group
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
     * Update a group.
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     * @Route("/{uuid}", name="_update", methods={"PATCH"})
     */
    public function update(string $uuid, Request $request): Response
    {
        return parent::update($uuid, $request);
    }

    /**
     * Retrieve the class of the managed entity.
     *
     * @return string
     */
    protected function getEntityClass(): string
    {
        return Group::class;
    }
}