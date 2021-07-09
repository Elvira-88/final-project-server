<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Repository\CoursesRepository;
use App\Repository\TeachersRepository;
use App\Service\CourseNormalize;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            
            $user = !$this->isGranted('ROLE_ADMIN') ? $this->getUser() : null;
            $data[] = $courseNormalize->courseNormalize($courses, 'course', $user);
            // $data[] = $courseNormalize->courseNormalize($courses);

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

     /**
     * @Route(
     *      "",
     *      name="post",
     *      methods={"POST"}
     * )
     *  @IsGranted("ROLE_ADMIN")
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        TeachersRepository $teachersRepository,
        CourseNormalize $courseNormalize,       
        SluggerInterface $slug 
        ): Response {
        $data = json_decode($request->getContent());
                        
        $teacher = $teachersRepository->find($data->teacher_id);

        $course = new Courses();

        $course->setName($data->name);
        $course->setDescription($data->description);
        $course->setTeacher($teacher);
        $course->setDuration($data->duration);
        $course->setPrice($data->price);

        $errors = $validator->validate($course);

        if(count($errors) > 0) {
            $dataErrors = [];

            /** @var \Symfony\Component\Validator\ConstraintViolation $error */
            foreach($errors as $error) {
                $dataErrors[] = $error->getMessage();
            }

            return $this->json([
                'status' => 'error',
                'data' => [
                    'errors' => $dataErrors
                ],
            ],
            Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($course);       

        $entityManager->flush();

        dump($course);

        return  $this->json(
            $courseNormalize->courseNormalize($course),
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                'api_courses_get',
                [
                    'id' => $course->getId()
                ]
                )
            ]

        );
    }

}
