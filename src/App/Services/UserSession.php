<?php

namespace App\Services;

use App\Entities\User;
use Lcobucci\JWT\Builder;

class UserSession
{
    const EXPIRATION_TIME = 3600;

    public function createNewToken(User $user): string
    {
        $builder = new Builder();

        $token = $builder
            ->setIssuer('')
            ->setAudience('')
            ->setId(uniqid())
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration(time() + self::EXPIRATION_TIME)
            ->getToken();
        
        return (string) $token;
    }
}