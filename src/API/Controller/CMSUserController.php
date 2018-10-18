<?php

namespace Siqu\CMS\API\Controller;

use Siqu\CMS\API\Form\CMSUserType;
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
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $user = new CMSUser();

        return $this->createResponse([$user], Response::HTTP_OK, $request);
    }

    /**
     * Read a specific user
     *
     * @param string $uuid
     * @param Request $request
     * @return Response
     * @Route("/{uuid}", name="_show", methods={"GET"})
     */
    public function show(string $uuid, Request $request): Response
    {
        $user = $this->getDoctrine()->getRepository(CMSUser::class)->findOneBy([
            'uuid' => $uuid
        ]);

        if(!$user) {
            return $this->createResponse([
                'message' => 'No user found with uuid ' . $uuid
            ], Response::HTTP_NOT_FOUND, $request);
        }

        return $this->createResponse($user, Response::HTTP_OK, $request);
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
        $data = json_decode($request->getContent(), true);
        $user = new CMSUser();

        $form = $this->createForm(CMSUserType::class, $user);

        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->createResponse($user, Response::HTTP_CREATED, $request);
        }

        return $this->createResponse($form->getErrors(), Response::HTTP_BAD_REQUEST, $request);
    }
}