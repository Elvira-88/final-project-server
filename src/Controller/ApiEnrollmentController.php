<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/enrollments", name="api_enrollment_")
 */

class ApiEnrollmentController extends AbstractController
{
    /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}
     * )
     */
  
    public function index(): Response
    {
        return $this->render('api_users_courses/index.html.twig', [
            'controller_name' => 'ApiEnrollmentController',
        ]);
    }
}
