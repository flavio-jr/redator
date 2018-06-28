<?php

namespace App\Exceptions;

class UserNotAllowedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The user can\'t complete the operation');
    }
}