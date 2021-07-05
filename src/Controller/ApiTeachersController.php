<?php

namespace App\Controller;

use App\Repository\TeachersRepository;
use App\Service\TeacherNormalize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Teachers;
use Doctrine\ORM\EntityManagerInterface;

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
        Request $request
        ): Response
    {
        $data = json_decode($request->getContent());     

        $teacher->setAvatar($data->avatar);
        $teacher->setName($data->name);
        $teacher->setLastName($data->lastName);
        $teacher->setDescription($data->description);
        // $teacher->setCourse($data->course);
        
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT
           
        );
    }

}
