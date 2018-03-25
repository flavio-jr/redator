<?php

namespace Tests\App\Unit\Tests;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;

class UserSessionTest extends TestCase
{
    use DatabaseRefreshTable;

    private $userSession;

    public function setUp()
    {
        parent::setUp();

        $this->userSession = $this->container->get('UserSession');
    }

    public function testCreateTokenForRegisteredUser()
    {
        $user = $this->container->get('App\Dumps\UserDump')->create();

        $jwt = $this->userSession->createNewToken($user);

        $this->assertNotNull($jwt);
    }

    public function testShouldReturnTrueForValidToken()
    {
        $user = $this->container->get('App\Dumps\UserDump')->create();

        $jwt = $this->userSession->createNewToken($user);
        
        $validToken = $this->userSession->isValidToken($jwt);

        $this->assertTrue($validToken);
    }
}