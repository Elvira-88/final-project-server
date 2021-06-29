<?php

namespace App\Controller;

use App\Repository\CoursesRepository;
use App\Service\CourseNormalize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/courses", name="api_courses_")
 */
class ApiCoursesController extends AbstractController
{
    /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}
     * )
     */
    public function index(Request $request, CoursesRepository $coursesRepository, CourseNormalize $courseNormalize): Response
    {
        $result = $coursesRepository->findAll();

        $data = [];

        foreach($result as $courses) {
            $data[] = $courseNormalize->courseNormalize($courses);

        }
        return $this->json($data);      
    }

     /**
     * @Route(
     *      "/{id}",
     *      name="get",
     *      methods={"GET"},
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     */
    public function show(
        int $id, 
        CoursesRepository $coursesRepository,
        CourseNormalize $courseNormalize
        ): Response
    {
        $data = $coursesRepository->find($id);

        dump($id);
        dump($data);

        return $this->json($courseNormalize->courseNormalize($data));
    }
}
