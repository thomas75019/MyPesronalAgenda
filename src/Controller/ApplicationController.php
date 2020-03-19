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
     * @Route("/", name="dashboard", methods={"GET"})
     *
     * @param ApplicationRepository $repository
     *
     * @return Response
     */
    public function index() : Response
    {
        $manager = $this->getDoctrine()->getManager()->getRepository(Application::class);
        $applications = $manager->findAll();

        return $this->render('application/viewAll.html.twig', [
            'applications' => $applications
        ]);
    }

    /**
     * @Route("/application/{id}", name="view_one_application", methods={"GET"})
     *
     * @param string $id
     * @param ApplicationRepository $repository
     *
     * @return Response
     */
    public function viewOneApplication($id, ApplicationRepository $repository, Request $request)
    {
        $application = $repository->find($id);

        return $this->render('application/viewOne.html.twig', [
            'application' => $application
        ]);
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
     * @Route("new/application", name="new_application", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createApplication(Request $request)
    {
        $application = new Application();

        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($application);
            $entityManager->flush();

            $this->addFlash('success', 'Application created');

            return $this->redirectToRoute('dashboard');
        }

        //need to change to render the form
        return $this->render('application/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("update/application/{id}", name="update_application", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Application $application
     *
     * @return Response|RedirectResponse
     */
    public function updateApplication(Request $request, Application $application)
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        //To change to render with Twig
        return $this->render('update_application', ['id' => $application->getId()]);
    }
}
