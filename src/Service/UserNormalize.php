<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\UrlHelper;

class UserNormalize {
    private $urlHelper;

    public function __construct(UrlHelper $constructorDeURL)
    {
        $this->urlHelper = $constructorDeURL;
    }

    /**
     * Normalize a user.
     * 
     * @param Users $user
     * 
     * @return array|null
     */
    public function UserNormalize (User $user): ?array {
  
        return [
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'dni' => $user->getDni(),          
            'phone' => $user->getPhone(),
            'address' => $user->getAddress(),
            'email' => $user->getEmail(),
        ];
    }
}