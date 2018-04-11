<?php

namespace App\Dumps;

use App\Services\Persister;
use App\Repositories\UserRepository;
use App\Entities\User;
use Faker\Generator;

class UserDump implements DumpInterface
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
     * Creates an new User without persisting it
     * @method make
     * @param array $override
     * @return User
     */
    public function make(array $override = [])
    {
        $user = new User();

        $user->setUsername($override['username'] ?? $this->faker->userName);
        $user->setName($override['name'] ?? $this->faker->name);
        $user->setEmail($override['email'] ?? $this->faker->safeEmail);
        $user->setPassword($override['password'] ?? $this->faker->password);

        return $user;
    }

    /**
     * Creates an new user, persisting it
     * @method create
     * @param array $override
     * @return User
     */
    public function create(array $override = [])
    {
        $user = $this->make($override);

        $this->persister->persist($user);

        return $user;
    }
}