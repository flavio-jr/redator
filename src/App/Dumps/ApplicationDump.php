<?php

namespace App\Dumps;

use App\Entities\Application;
use App\Services\Persister;
use Faker\Generator;
use App\Services\Slugify\SlugifyInterface;

class ApplicationDump implements DumpInterface
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

    /**
     * The user dump
     * @var UserDump
     */
    private $userDump;

    /**
     * The slugifier service
     * @var SlugifyInterface
     */
    private $slugifier;

    public function __construct(
        Generator $faker,
        Persister $persister,
        UserDump $userDump,
        SlugifyInterface $slugifier
    ) {
        $this->faker = $faker;
        $this->persister = $persister;
        $this->userDump = $userDump;
        $this->slugifier = $slugifier;
    }

    /**
     * Create an Application without persisting
     * @method make
     * @param array $override
     * @return Application
     */
    public function make(array $override = [])
    {
        $application = new Application();

        $application->setName($override['name'] ?? $this->faker->name);
        $application->setSlug($this->slugifier->slugify($application->getName()));
        $application->setDescription($override['description'] ?? $this->faker->text);
        $application->setUrl($override['url'] ?? $this->faker->domainName);
        $application->setType($override['type'] ?? rand(0, 1) === 0 ? 'LP' : 'NL');
        $application->setAppOwner($override['owner'] ?? $this->userDump->create());

        return $application;
    }

    /**
     * Creates a new Application, persisting it
     * @method create
     * @param array $override
     * @return Application
     */
    public function create(array $override = [])
    {
        $application = $this->make($override);

        $this->persister->persist($application);

        return $application;
    }
}