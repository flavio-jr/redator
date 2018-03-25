<?php

namespace App\Dumps;

use App\Services\Persister;
use App\Repositories\UserRepository;
use App\Entities\User;
use Faker\Generator;

class UserDump implements DumpInterface
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
        $user = new User();

        $user->setUsername($override['username'] ?? $this->faker->userName);
        $user->setName($override['name'] ?? $this->faker->name);
        $user->setPassword($override['password'] ?? $this->faker->password);

        return $user;
    }

    public function create(array $override = [])
    {
        $user = $this->make($override);

        $this->persister->persist($user);

        return $user;
    }
}