<?php

namespace App\Controller;

use App\Repository\TeachersRepository;
use App\Repository\CoursesRepository;
use App\Service\TeacherNormalize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Teachers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        TeachersRepository $teachersRepository,
        TeacherNormalize $teacherNormalize        
        ): Response
    {
        $data = $teachersRepository->find($id);

        dump($id);
        dump($data);

        return $this->json($teacherNormalize->teacherNormalize($data));
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
        Teachers $teacher,
        EntityManagerInterface $entityManager        
        ): Response
    {
        dump($teacher);
       
        $entityManager->remove($teacher);
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
        Teachers $teacher,
        EntityManagerInterface $entityManager,
        CoursesRepository $courseRepository,
        Request $request
        ): Response
    {
        $data = json_decode($request->getContent());  
        
        $course = $courseRepository->find($data->course_id);

        // $teacher->setAvatar($data->avatar);
        $teacher->setName($data->name);
        $teacher->setLastName($data->lastName);
        $teacher->setDescription($data->description);
        $teacher->setCourses($course);
        
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
        CoursesRepository $courseRepository,
        TeacherNormalize $teacherNormalize,       
        SluggerInterface $slug 
        ): Response {
        $data = json_decode($request->getContent());
            
        $course = $courseRepository->find($data->course_id); 
        dump($course);  

        $teacher = new Teachers();

        $teacher->setName($data->name);
        $teacher->setLastName($data->lastName);
        $teacher->setDescription($data->description);
        $teacher->setCourses($course);     

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
            $teacherNormalize->teacherNormalize($teacher),
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                'api_teachers_get',
                [
                    'id' => $teacher->getId()
                ]
                )
            ]

        );
    }

    /**
    * @Route(
    *      "/updateimg/{id}",
    *      name="updateImg",
    *      methods={"POST"},
    *      requirements={
    *          "id": "\d+"
    *      }
    * )
    */
    public function updateImg(
        Teachers $teacher, 
        Request $request, 
        EntityManagerInterface $entityManager):Response {
     
        if($request->files->has('avatar')) {
            $avatarFile = $request->files->get('avatar');

            $newFilename = uniqid().'.'.$avatarFile->guessExtension();

            try {
                $avatarFile->move(
                    $request->server->get('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR . 'teachers/avatar', 
                    $newFilename 
                );
            } catch (FileException $error) {
                throw new \Exception($error->getMessage());
            }

            $teacher->setAvatar($newFilename);
        }

        $entityManager->flush();

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

}
