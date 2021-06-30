<?php

namespace App\Controller;

use App\Repository\TeachersRepository;
use App\Repository\UserRepository;
use App\Service\CourseNormalize;
use App\Service\EnrollmentNormalize;
use App\Service\TeacherNormalize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users", name="api_users_")
 */

class ApiUsersController extends AbstractController
{
     /**
     * @Route(
     *      "/courses",
     *      name="get_courses",
     *      methods={"GET"}
     * )
     */

    public function courses(EnrollmentNormalize $enrollmentNormalize, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(1); //$this->getUser();

        $data = [];

        foreach($user->getEnrollments() as $enrollment) {
            $data[] = $enrollmentNormalize->enrollmentNormalize($enrollment, 'enrollment');
        }

        return $this->json($data);
    }    
    
}
