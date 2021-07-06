<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TeachersRepository;
use App\Repository\UserRepository;
use App\Service\CourseNormalize;
use App\Service\EnrollmentNormalize;
use App\Service\TeacherNormalize;
use App\Service\UserNormalize;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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


    /**
     * @Route(
     *      "/register",
     *      name="post",
     *      methods={"POST"}
     * )
     */
    public function add(
        UserNormalize $userNormalize,
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,     
        UserPasswordHasherInterface $hasher
        ): Response {
        $data = json_decode($request->getContent());
                 
        $user = new User();

        $user->setName($data->name);
        $user->setLastName($data->lastName);
        $user->setDni($data->dni);
        $user->setPhone($data->phone);
        $user->setAddress($data->address);
        $user->setEmail($data->email);

        $hash = $hasher->hashPassword($user, $data->password);
        $user->setPassword($hash);                        

        $errors = $validator->validate($user);

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

        $entityManager->persist($user);

        $entityManager->flush();

       
        return $this->json(
            $userNormalize->UserNormalize($user),
            Response::HTTP_CREATED,        

        );
    }
    
}
