<?php

namespace App\Service;

use App\Entity\Teachers;
use Symfony\Component\HttpFoundation\UrlHelper;

class TeacherNormalize {
    private $urlHelper;

    public function __construct(UrlHelper $constructorDeURL)
    {
        $this->urlHelper = $constructorDeURL;
    }

    /**
     * Normalize a teacher.
     * 
     * @param Teachers $teacher
     * 
     * @return array|null
     */
    public function teacherNormalize (Teachers $teachers): ?array {
        $courses= [];

        foreach($teachers->getCourses() as $course) {
            array_push($courses, [
                'id' => $course->getId(),    
                'name' => $course->getName(),    
            ]);
        }

        // $avatar = '';
        // if($employee->getAvatar()) {
        //     $avatar = $this->urlHelper->getAbsoluteUrl('/employee/avatar/'.$employee->getAvatar());
        // }

        return [
            'id' => $teachers->getId(),
            'avatar' => $this->urlHelper->getAbsoluteUrl('/teacher/avatar/'.$teachers->getAvatar()),
            'name' => $teachers->getName(),
            'lastName' => $teachers->getLastName(),
            'description' => $teachers->getDescription(),
            // 'course' => [
            //     'id' => $teachers->getCourses()->getId(),
            //     'name' => $teachers->getCourses()->getName(),
            // ],
            'courses' => $courses,
            
        ];
    }
}