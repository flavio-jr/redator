<?php

namespace App\Database\Types;

final class UserType
{
    /**
     * Master user
     * Can allow the register of new users
     * @var string
     */
    public const M = 'Master';
    
    /**
     * Writter user
     * Can create new apps and publications
     * in those apps
     * 
     * @var string
     */
    public const W = 'Writter';

    /**
     * Get all user types
     * @method getTypes
     * @return array
     */
    public static function getTypes(): array
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }
}