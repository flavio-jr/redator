<?php

namespace App\Exceptions;

class UserNotAllowedToUpdatePublication extends \Exception
{
    public function __construct()
    {
        parent::__construct('The user is not allowed to update the publication');
    }
}