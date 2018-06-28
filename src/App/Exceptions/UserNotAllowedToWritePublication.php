<?php

namespace App\Exceptions;

class UserNotAllowedToWritePublication extends \Exception
{
    public function __construct()
    {
        parent::__construct('The current user is not allowed to write publication in the app');
    }
}