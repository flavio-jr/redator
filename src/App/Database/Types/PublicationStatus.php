<?php

namespace App\Database\Types;

use App\Support\Traits\TranslateEnum;

final class PublicationStatus
{
    use TranslateEnum;

    /**
     * Draft publications is'nt
     * available to the public API
     * 
     * @var string
     */
    public const DF = 'Draft';

    /**
     * The publication are 
     * available to be delivered
     * by the public API
     * 
     * @var string
     */
    public const PB = 'Published';
}
