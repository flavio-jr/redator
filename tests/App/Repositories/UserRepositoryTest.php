<?php

namespace Tests\App\Repositories;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;

class UserRepositoryTest extends TestCase
{
    use DatabaseRefreshTable;

    private $userRepository;

    public function setUp()
    {
        $this->userRepository = self::$application->getContainer()->get('UserRepository');
    }
}