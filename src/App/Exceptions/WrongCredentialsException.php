<?php

namespace App\Exceptions;

use Exception;

class WrongCredentialsException extends Exception
{
    public function __construct()
    {
        $message = "FORBIDDEN: THE GIVEN CREDENTIALS ARE INVALID";
        parent::__construct($message, 400, null); 
    }
}