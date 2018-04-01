<?php

namespace App\Database\Types;

final class ApplicationType
{
    /**
     * Newsletter type
     * @var string
     */
    public const NL = 'Newsletter';
    
    /**
     * Landing page type
     * @var string
     */
    public const LP = 'Landing page';

    /**
     * Get all aplication types
     * @method getApplicationTypes
     * @return array
     */
    public static function getApplicationTypes(): array
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }
}