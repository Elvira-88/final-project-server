<?php

namespace App\Service;

use App\Entity\Courses;
use App\Entity\User;
use Symfony\Component\HttpFoundation\UrlHelper;

class CourseNormalize {
    private $urlHelper;

    public function __construct(UrlHelper $constructorDeURL)
    {
        $this->urlHelper = $constructorDeURL;
    }

    /**
     * Normalize a course.
     * 
     * @param Courses $course
     * 
     * @return array|null
     */
    public function courseNormalize (Courses $courses, $target = 'course', User $user = null): ?array {
        $data = [
            'id' => $courses->getId(),            
            'name' => $courses->getName(),
            'description' => $courses->getDescription(),
            'teacher' => [
                'id' => $courses->getTeacher()->getId(),
                'avatar' => $this->urlHelper->getAbsoluteUrl('/teacher/avatar/'.$courses->getTeacher()->getAvatar()),
                'name' => $courses->getTeacher()->getName(),
                'lastName' => $courses->getTeacher()->getLastName(),
            ],
            'duration' => $courses->getDuration()           
        ];

        if ($target === 'course') {
            $data['price'] = $courses->getPrice();
        }
      
        dump($user);
        if ($user) {
            foreach($user->getEnrollments() as $enrollment){
                dump($enrollment);
                dump($courses);
                if($enrollment->getCourse()->getId() === $courses->getId()) {
                    $data['coursed'] = true;
                    dump($data);
                    break;
                }
            }
        }

        return $data;
    }
}