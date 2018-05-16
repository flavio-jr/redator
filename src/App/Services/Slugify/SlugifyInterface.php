<?php

namespace App\Services\Slugify;

interface SlugifyInterface
{
    /**
     * Take a string as argument and turn it on an slug
     * @method slugify
     * @param string $text
     * @return string
     */
    public function slugify(string $text): string;
}