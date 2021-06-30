<?php

namespace App\Service;

use App\Entity\Enrollment;
use App\Entity\Teachers;
use Symfony\Component\HttpFoundation\UrlHelper;

class EnrollmentNormalize {
    private $courseNormalize;

    public function __construct(CourseNormalize $courseNormalize)
    {
        $this->courseNormalize = $courseNormalize;
    }

    /**
     * Normalize a enrollment.
     * 
     * @param Enrollments $enrollment
     * 
     * @return array|null
     */
    public function enrollmentNormalize (Enrollment $enrollment): ?array {
        return [
            'date' => '',
            'course' => $this->courseNormalize->courseNormalize($enrollment->getCourse(), 'enrollment')
            
        ];
    }
}