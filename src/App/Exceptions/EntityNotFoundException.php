<?php

namespace App\Exceptions;

use Exception;

class EntityNotFoundException extends Exception
{
    /**
     * The entity name
     * @var string
     */
    private $entityName;

    public function __construct(
        string $entityName,
        int $code = 0,
        Exception $previous = null
    ) {
        $message = "The {$entityName} was not found with the given data";
        parent::__construct($message, $code, $previous); 

        $this->entityName = $entityName;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }
}