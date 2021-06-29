<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUsersCoursesController extends AbstractController
{
    /**
     * @Route("/api/users/courses", name="api_users_courses")
     */
    public function index(): Response
    {
        return $this->render('api_users_courses/index.html.twig', [
            'controller_name' => 'ApiUsersCoursesController',
        ]);
    }
}
