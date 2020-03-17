<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/application/new", name="new_application")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createApplication(Request $request) : RedirectResponse
    {
        $application = new Application();

        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($application);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        //need to change to render the form
        return $this->redirectToRoute('new_application');
    }


    /**
     * @Route("update/application/{id}", name="update_application")
     *
     * @param Request $request
     * @param Application $application
     *
     * @return RedirectResponse
     */
    public function updateApplication(Request $request, Application $application)
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        //To change to render with Twig
        return $this->redirectToRoute('update_application', ['id' => $application->getId()]);
    }
}
