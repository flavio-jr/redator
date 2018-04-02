<?php

namespace App\Exceptions;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(
        string $entityName,
        int $code = 0,
        Exception $previous = null
    ) {
        $message = "The {$entityName} was not found with the given data";
        parent::__construct($message, $code, $previous); 
    }
}