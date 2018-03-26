<?php

namespace Tests\App\Integration;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;

class UserAuthenticationTest extends TestCase
{
    use DatabaseRefreshTable;

    private $userRepository;
    private $userSession;
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = $this->container->get('UserRepository');
        $this->userSession = $this->container->get('UserSession');
        $this->userDump = $this->container->get('App\Dumps\UserDump');
    }

    public function testShouldGenerateTokenForRegisteredUser()
    {
        $username = 'newton';
        $password = 'f=m*a';

        $this->userDump->create(['username' => $username, 'password' => $password]);

        $user = $this->userRepository->getUserByCredentials($username, $password);

        $token = $this->userSession->createNewToken($user->getId());

        $this->assertNotNull($token);
    }
}