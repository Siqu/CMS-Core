<?php

namespace Siqu\CMS\API\Controller;

use Siqu\CMS\Core\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PageController
 * @package Siqu\CMS\API\Controller
 * @Route("/page", name="api_page")
 */
class PageController extends APIController
{
    /**
     * Create a new page.
     *
     * @return Response
     * @Route("", name="_create", methods={"POST"})
     */
    public function create(): Response
    {
        return parent::create();
    }

    /**
     * Delete a page.
     *
     * @param string $uuid
     * @return Response
     * @Route("/{uuid}", name="_delete", methods={"DELETE"})
     */
    public function delete(string $uuid): Response
    {
        return parent::delete($uuid);
    }

    /**
     * Read all pages.
     *
     * @return Response
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(): Response
    {
        return parent::index();
    }

    /**
     * Read a specific page
     *
     * @param string $uuid
     * @return Response
     * @Route("/{uuid}", name="_show", methods={"GET"})
     */
    public function show(string $uuid): Response
    {
        return parent::show($uuid);
    }

    /**
     * Update a page.
     *
     * @param string $uuid
     * @return Response
     * @Route("/{uuid}", name="_update", methods={"PATCH"})
     */
    public function update(string $uuid): Response
    {
        return parent::update($uuid);
    }

    /**
     * Retrieve the class of the managed entity.
     *
     * @return string
     */
    protected function getEntityClass(): string
    {
        return Page::class;
    }
}
