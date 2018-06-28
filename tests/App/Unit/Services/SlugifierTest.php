<?php

namespace Tests\App\Unit\Services;

use Tests\TestCase;
use App\Services\Slugify\Slugify;

class SlugifierTest extends TestCase
{
    /**
     * @var Slugify
     */
    private $slugify;

    public function setUp()
    {
        parent::setUp();
        
        $this->slugify = $this->container->get(Slugify::class);
    }

    public function testStringMustHaveSpaceReplacedByHifen()
    {
        $stringWithSpaces = 'Have you ever danced with the devil in the pale moonlight?';

        $slugfied = $this->slugify->slugify($stringWithSpaces);

        $this->assertFalse((bool) preg_match('/[\sA-Z]+/', $slugfied));
    }
}