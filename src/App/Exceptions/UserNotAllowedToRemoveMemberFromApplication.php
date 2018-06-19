<?php

namespace App\Exceptions;

class UserNotAllowedToRemoveMemberFromApplication extends \Exception
{
    public function __construct()
    {
        parent::__construct('The logged user is not allowed to remove the membership');
    }
}