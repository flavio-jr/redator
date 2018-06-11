<?php

namespace App\Exceptions;

class UserNotAllowedToAddMemberToApplication extends \Exception
{
    public function __construct()
    {
        parent::__construct('The user is not allowed to add members to apps');
    }
}