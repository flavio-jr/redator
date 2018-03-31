<?php

namespace App\Dumps;

use App\Services\Persister;
use Faker\Generator;
use App\Entities\Publication;

class PublicationDump implements DumpInterface
{
    private $faker;
    private $persister;
    private $applicationDump;
    private $categoryDump;

    public function __construct(
        Generator $faker,
        Persister $persister,
        ApplicationDump $applicationDump,
        CategoryDump $categoryDump
    ) {
        $this->faker = $faker;
        $this->persister = $persister;
        $this->applicationDump = $applicationDump;
        $this->categoryDump = $categoryDump;
    }

    public function make(array $override = [])
    {
        $publication = new Publication();

        $publication->setTitle($override['title'] ?? $this->faker->realText(80));
        $publication->setDescription($override['description'] ?? $this->faker->text(120));
        $publication->setBody($override['body'] ?? $this->faker->randomHtml(2, 3));
        $publication->setApplication($override['application'] ?? $this->applicationDump->create());
        $publication->setCategory($override['category'] ?? $this->categoryDump->create());

        return $publication;
    }

    public function create(array $override = [])
    {
        $application = $this->make($override);

        $this->persister->persist($application);

        return $application;
    }
}