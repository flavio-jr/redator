<?php

namespace App\Exceptions;

class UserNotAllowedToCreateApplication extends \Exception
{
    public function __construct()
    {
        parent::__construct('The user is not allowed to create applications');
    }
}