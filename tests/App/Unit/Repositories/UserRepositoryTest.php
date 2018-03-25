<?php

namespace Tests\App\Unit\Repositories;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;

class UserRepositoryTest extends TestCase
{
    use DatabaseRefreshTable;

    private $userRepository;
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = $this->container->get('UserRepository');
        $this->userDump = $this->container->get('App\Dumps\UserDump');    
    }

    public function testCreateUser()
    {
        $userData = $this->userDump->make();

        $user = $this->userRepository->create($userData->toArray());

        $this->assertDatabaseHave($user);
    }
}