<?php

namespace App\Services\Slugify;

use Cocur\Slugify\Slugify as Slugifier;

final class Slugify implements SlugifyInterface
{
    /**
     * The slugfier component
     * @var Slugifier
     */
    private $slugfier;

    public function __construct(Slugifier $slugfier)
    {
        $this->slugfier = $slugfier;
    }

    /**
     * @inheritdoc
     */
    public function slugify(string $text): string
    {
        return $this->slugfier->slugify($text);  
    }
}