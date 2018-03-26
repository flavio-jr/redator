<?php

namespace App\Exceptions;

use Exception;

class UniqueFieldException extends Exception
{
    public function __construct(
        string $fieldName,
        int $code = 0,
        Exception $previous = null
    ) {
        $message = "The {$fieldName} value is already taken";
        parent::__construct($message, $code, $previous); 
    }
}