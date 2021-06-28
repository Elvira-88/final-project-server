<?php

namespace App\Controller;

use App\Repository\TeachersRepository;
use App\Service\TeacherNormalize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/teachers", name="api_teachers_")
 */

class ApiTeachersController extends AbstractController
{
     /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}
     * )
     */

    public function index(Request $request, TeachersRepository $teachersRepository, TeacherNormalize $teacherNormalize): Response

    {
        $result = $teachersRepository->findAll();

        $data = [];

        foreach($result as $teachers) {
            $data[] = $teacherNormalize->teacherNormalize($teachers);

        }
        return $this->json($data);     

    }    
    
}
