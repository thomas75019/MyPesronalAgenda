<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Interview;
use App\Form\InteviewType;
use App\Repository\ApplicationRepository;
use App\Repository\InterviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterviewController extends AbstractController
{
    /**
     * @Route("/interviews", name="interview_index")
     *
     * @param InterviewRepository $repository
     *
     * @return Response
     */
    public function index(InterviewRepository $repository)
    {
        $interviews = $repository->findAll();

        return $this->render('interview/viewAll.html.twig', [
            'interviews' => $interviews
        ]);
    }

    /**
     * @Route("/interview/{id}", name="view_one_interview")
     *
     * @param InterviewRepository $repository
     * @param integer $id
     *
     * @return Response
     */
    public function viewOneInterview(InterviewRepository $repository, $id)
    {
        $interview = $repository->find($id);

        return $this->render('interview/viewOne.html.twig', [
            'interview' => $interview
        ]);
    }

    /**
     * @Route("/delete/interview/{id}", name="delete_interview")
     *
     * @param integer $id
     * @param InterviewRepository $repository
     *
     * @return RedirectResponse
     */
    public function deleteInterview($id, InterviewRepository $repository)
    {
        $interview = $repository->find($id);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($interview);
        $entityManager->flush();

        return $this->redirectToRoute('interview_index');
    }

    /**
     * @Route("/interview/create/{id_application}", name="create_interview")
     *
     * @param Request $request
     * @param ApplicationRepository $repository
     *
     * @return Response|RedirectResponse
     */
    public function createInterview(Request $request)
    {
        $interview = new Interview();
        $form = $this->createForm(InteviewType::class, $interview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $application_id = $request->get('id_application');
            $application = $this->getDoctrine()->getRepository(Application::class)->find($application_id);

            $interview->setApplication($application);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($interview);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('interview/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/interview/{id}", name="update_interview")
     *
     * @param Interview $interview
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface|RedirectResponse
     */
    public function updateInterview(Interview $interview, Request $request)
    {
        $form = $this->createForm(InteviewType::class, $interview);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute('view_one_interview', [
                'id' => $interview->getId()
            ]);
        }

        return $form;
    }

}
