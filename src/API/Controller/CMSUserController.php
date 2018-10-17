<?php

namespace Siqu\CMS\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CMSUserController
 * @package Siqu\CMS\API\Controller
 * @Route("/user", name="api_user")
 */
class CMSUserController extends Controller
{
    /**
     * @return JsonResponse
     * @Route("", name="_index")
     */
    public function index(): JsonResponse {
        return new JsonResponse(['test']);
    }
}