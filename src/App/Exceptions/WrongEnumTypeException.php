<?php

namespace App\Exceptions;

class WrongEnumTypeException extends \Exception
{
    public function __construct(string $value, array $enum)
    {
        parent::__construct("The value: $value does not satisfy the enum: [" . implode(', ', array_keys($enum)) . "]");
    }
}