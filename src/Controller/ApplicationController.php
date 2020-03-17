<?php

namespace App\Controller;

use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     *
     * @param ApplicationRepository $repository
     *
     * @return JsonResponse
     */
    public function index(ApplicationRepository $repository) : JsonResponse
    {
        $applications = $repository->findAll();

        return new JsonResponse($applications, 200);
    }

    /**
     * @Route("/applcations/{id}", name="view_one_application")
     *
     * @param string $id
     * @param ApplicationRepository $repository
     *
     * @return Response
     */
    public function viewOneApplication($id, ApplicationRepository $repository)
    {
        $application = $repository->find($id);

        return new Response($application);
    }

    /**
     * @Route("/delete/application/{id}", name="delete_one_application")
     *
     * @param integer $id
     * @param ApplicationRepository $repository
     *
     * @return RedirectResponse
     */
    public function deleteApplication($id, ApplicationRepository $repository) : RedirectResponse
    {
        $application = $repository->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($application);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard');
    }
}
