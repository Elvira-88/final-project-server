<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TeachersRepository;
use App\Repository\UserRepository;
use App\Service\CourseNormalize;
use App\Service\EnrollmentNormalize;
use App\Service\TeacherNormalize;
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
     *      "",
     *      name="post",
     *      methods={"POST"}
     * )
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,     
        UserPasswordHasherInterface $hasher
        ): Response {
        $data = json_decode($request->getContent());
        $data->name;
        dump($data);
        die();
                 
        $user = new User();

        $user->setName($data->name);
        $user->setLastName($data->get('lastname'));
        $user->setDni($data->get('dni'));
        $user->setPhone($data->get('phone'));
        $user->setAddress($data->get('adress'));
        $user->setEmail($data->get('email'));

        $hash = $hasher->hashPassword($user, $data->password);
        $user->setPassword($hash);
        //password?                   

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

       
        return  $this->json(
           
            Response::HTTP_CREATED,        

        );
    }
    
}
