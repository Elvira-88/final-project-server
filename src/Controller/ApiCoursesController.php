<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Repository\CoursesRepository;
use App\Service\CourseNormalize;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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


    /**
     * @Route(
     *      "/{id}",
     *      name="delete",
     *      methods={"DELETE"},
     *      requirements={
     *          "id": "\d+"     
     *      }     
     * )
     *  @IsGranted("ROLE_ADMIN")
     */    
    public function remove(
        Courses $course,
        EntityManagerInterface $entityManager
        ): Response
    {
        dump($course);
       
        $entityManager->remove($course);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
        
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="put",
     *      methods={"PUT"},
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     *  @IsGranted("ROLE_ADMIN")
     */
    public function update(
        Courses $course,
        EntityManagerInterface $entityManager,
        Request $request
        ): Response
    {
        $data = json_decode($request->getContent());

        $course->setName($data->name);
        $course->setDescription($data->description);
        $course->setTeacher($data->teacher);
        $course->setDuration($data->duration);
        $course->setPrice($data->price);

        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT
           
        );
    }
}
