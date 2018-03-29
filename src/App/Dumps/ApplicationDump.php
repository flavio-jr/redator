<?php

namespace App\Dumps;

use App\Entities\Application;
use App\Services\Persister;
use Faker\Generator;

class ApplicationDump implements DumpInterface
{
    private $faker;
    private $persister;
    private $userDump;

    public function __construct(
        Generator $faker,
        Persister $persister,
        UserDump $userDump
    ) {
        $this->faker = $faker;
        $this->persister = $persister;
        $this->userDump = $userDump;
    }

    public function make(array $override = [])
    {
        $application = new Application();

        $application->setName($override['name'] ?? $this->faker->name);
        $application->setDescription($override['description'] ?? $this->faker->text);
        $application->setUrl($override['url'] ?? $this->faker->url);
        $application->setType($override['type'] ?? rand(0, 1) === 0 ? 'LP' : 'NL');
        $application->setAppOwner($override['owner'] ?? $this->userDump->create());

        return $application;
    }

    public function getUser(array $override)
    {
        if (isset($override['owner'])) {
            return $override['owner'];
        }

        return $this->userDump->create();
    }

    public function create(array $override = [])
    {
        $application = $this->make($override);

        $this->persister->persist($application);

        return $application;
    }
}