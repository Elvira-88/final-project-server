<?php

namespace App\Service;

use App\Entity\Courses;
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
    public function courseNormalize (Courses $courses): ?array {
      
        return [
            'avatar' => $this->urlHelper->getAbsoluteUrl('/teacher/avatar/'.$courses->getTeacher()->getAvatar()),
            'name' => $courses->getName(),
            'description' => $courses->getDescription(),
            'teacher' => [
                'id' => $courses->getTeacher()->getId(),
                'name' => $courses->getTeacher()->getName(),
            ],
            'duration' => $courses->getDuration(),
            'price' => $courses->getPrice(),            
        ];
    }
}