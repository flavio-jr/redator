<?php

namespace App\Services;

use App\Entities\User;

class UserSession
{
    public function createNewToken(User $user): string
    {
        return '';
    }
}