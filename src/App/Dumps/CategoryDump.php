<?php

namespace App\Dumps;

use App\Services\Persister;
use Faker\Generator;
use App\Entities\Category;

class CategoryDump implements DumpInterface
{
    private $faker;
    private $persister;

    public function __construct(Generator $faker, Persister $persister)
    {
        $this->faker = $faker;
        $this->persister = $persister;
    }

    public function make(array $override = [])
    {
        $category = new Category();

        $category->setName($override['name'] ?? $this->faker->name);

        return $category;
    }

    public function create(array $override = [])
    {
        $category = $this->make($override);

        $this->persister->persist($category);

        return $category;
    }
}