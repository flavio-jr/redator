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

    public function make()
    {
        $user = new User();

        $user->setUsername($this->faker->userName);
        $user->setName($this->faker->name);
        $user->setPassword($this->faker->password);

        return $user;
    }

    public function create()
    {
        $user = $this->make();

        $this->persister->persist($user);

        return $user;
    }
}