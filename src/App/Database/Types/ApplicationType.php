<?php

namespace App\Database\Types;

final class ApplicationType
{
    public const NL = 'Newsletter';

    public const LP = 'Landing page';

    public static function getApplicationTypes(): array
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }
}