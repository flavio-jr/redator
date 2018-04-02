<?php

namespace App\Dumps;

use App\Services\Persister;
use Faker\Generator;
use App\Entities\Category;

class CategoryDump implements DumpInterface
{
    /**
     * The faker library
     * @var Generator
     */
    private $faker;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(Generator $faker, Persister $persister)
    {
        $this->faker = $faker;
        $this->persister = $persister;
    }

    /**
     * Creates a new category without persisting it
     * @method make
     * @param array $override
     * @return Category
     */
    public function make(array $override = [])
    {
        $category = new Category();

        $category->setName($override['name'] ?? $this->faker->name);

        return $category;
    }

    /**
     * Creates a new category, persisting it
     * @method create
     * @param array $override
     * @return Category
     */
    public function create(array $override = [])
    {
        $category = $this->make($override);

        $this->persister->persist($category);

        return $category;
    }
}