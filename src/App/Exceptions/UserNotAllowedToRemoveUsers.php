<?php

namespace App\Exceptions;

class UserNotAllowedToRemoveUsers extends \Exception
{
    public function __construct()
    {
        parent::__construct('The current user can\'t destroy other users');
    }
}