<?php

namespace App\Controller;

use App\Entity\Interview;
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

}
