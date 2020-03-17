<?php

namespace App\Controller;

use App\Entity\Interview;
use App\Form\InteviewType;
use App\Repository\ApplicationRepository;
use App\Repository\InterviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InterviewController extends AbstractController
{
    /**
     * @Route("/interviews", name="interview_index")
     *
     * @param InterviewRepository $repository
     *
     * @return array $interviews
     */
    public function index(InterviewRepository $repository)
    {
        $interviews = $repository->findAll();

        return $interviews;
    }

    /**
     * @Route("/interview/{id}", name="view_one_interview")
     *
     * @param InterviewRepository $repository
     * @param integer $id
     *
     * @return Interview $interview
     */
    public function viewOneInterview(InterviewRepository $repository, $id)
    {
        $interview = $repository->find($id);

        return $interview;
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
     * @return \Symfony\Component\Form\FormInterface|RedirectResponse
     */
    public function createInterview(Request $request, ApplicationRepository $repository)
    {
        $interview = new Interview();
        $form = $this->createForm(InteviewType::class, $interview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $application_id = $request->get('id_application');
            $application = $repository->find($application_id);

            $interview->setApplication($application);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($interview);

            return $this->redirectToRoute('view_one_interview', [
                'id' => $application_id
            ]);
        }

        return $form;
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
