<?php

namespace App\Controller;

use App\Entity\Enrollment;
use App\Repository\CoursesRepository;
use App\Service\EnrollmentNormalize;
use App\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/enrollments", name="api_enrollments_")
 */

class ApiEnrollmentsController extends AbstractController
{
     /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}
     * )
     */

    public function index(Request $request, EnrollmentRepository $enrollmentRepository, EnrollmentNormalize $enrollmentNormalize): Response

    {
        $result = $enrollmentRepository->findAll();

        $data = [];

        foreach($result as $enrollment) {
            $data[] = $enrollmentNormalize->enrollmentNormalize($enrollment);

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
        EnrollmentRepository $enrollmentRepository,
        EnrollmentNormalize $enrollmentNormalize
        ): Response
    {
        $data = $enrollmentRepository->find($id);

        dump($id);
        dump($data);

        return $this->json($enrollmentNormalize->enrollmentNormalize($data));
    }

    /**
     * @Route(
     *      "",
     *      name="post",
     *      methods={"POST"}
     * )
     *  
     */
    public function add(
        Request $request,
        CoursesRepository $courseRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        EnrollmentNormalize $enrollmentNormalize,
        SluggerInterface $slug  
        ): Response {
        $data = json_decode($request->getContent());
        dump($data);                        
        
        $course = $courseRepository->find($data->course_id);  
        dump($course);     

        $enrollments = new Enrollment();

        $enrollments->setUser($this->getUser());
        $enrollments->setCourse($course);
        $enrollments->setDate(new \DateTimeImmutable());

        $entityManager->persist($enrollments);       
        $entityManager->flush();

        return  $this->json(
            $enrollmentNormalize->enrollmentNormalize($enrollments),
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                'api_enrollments_get',
                [
                    'id' => $enrollments->getId()
                ]
                )
            ]

        );
    }

}