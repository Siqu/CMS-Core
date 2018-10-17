<?php

namespace Siqu\CMS\API\Controller;

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
     * Read all CMSUser.
     *
     * @param Request $request
     * @return Response
     * @Route("", name="_index")
     */
    public function index(Request $request): Response {
        $user = new CMSUser();

        return $this->createResponse([$user], $request);
    }
}