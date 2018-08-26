<?php

namespace App\Support\Traits;

trait TranslateEnum
{
    /**
     * Get all the enum with its values
     * 
     * @method getEnum
     * @return array
     */
    public static function getEnum(): array
    {
        $reflection = new \ReflectionClass(__CLASS__);

        return $reflection->getConstants();
    }

    public static function isKeySet(string $key): bool
    {
        $reflection = new \ReflectionClass(__CLASS__);

        return (bool) $reflection->getConstant($key);
    }
}
