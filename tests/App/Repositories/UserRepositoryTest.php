<?php

namespace Tests\App\Repositories;

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

        $this->userRepository = self::$application->getContainer()->get('UserRepository');
        $this->userDump = self::$application->getContainer()->get('App\Dumps\UserDump');    
    }

    public function testCreateUser()
    {
        $userData = $this->userDump->make();

        $user = $this->userRepository->create($userData->toArray());

        $this->assertDatabaseHave($user);
    }
}