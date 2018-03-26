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

    public function testGetUserByCredentials()
    {
        $user = $this->userDump->create(['password' => '123']);

        $userHopingToBeAuthenticated = $this->userRepository
            ->getUserByCredentials($user->getUsername(), '123');

        $this->assertNotNull($userHopingToBeAuthenticated);
    }

    public function testShouldReturnNullWithWrongPassword()
    {
        $user = $this->userDump->create(['password' => '123']);

        $userGivingWrongPassword = $this->userRepository
            ->getUserByCredentials($user->getUsername(), '1234');

        $this->assertNull($userGivingWrongPassword);
    }

    public function testShouldReturnNullWithWrongUsername()
    {
        $user = $this->userDump->create(['username' => 'vader', 'password' => '123']);

        $userGivingWrongUsername = $this->userRepository
            ->getUserByCredentials('anakin', '123');

        $this->assertNull($userGivingWrongUsername);
    }
}