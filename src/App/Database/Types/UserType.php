<?php

namespace App\Database\Types;

final class UserType
{
    /**
     * Master user
     * Can allow the register of new users (partners and writters)
     * Can promote users levels
     * Can create new apps
     * Can deactivate or delete users (partners included)
     * Can deactivate or delete any app
     * Can disable the visibility of any publication or delete it
     * @var string
     */
    public const M = 'Master';
    
    /**
     * Partner user
     * Can allow the registration of new users (only writters)
     * Can create new applications
     * Can deactivate or delete apps that belong to him
     * Can disable the visibility of publications or delete it that belong to him
     * @var string
     */
    public const P = 'Partner';
    
    /**
     * Writter user
     * Can create publications in apps they are included
     * Can deactivate/delete publications they have written
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