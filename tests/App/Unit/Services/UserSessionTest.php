<?php

namespace Tests\App\Unit\Tests;

use Tests\TestCase;

class UserSessionTest extends TestCase
{
    private $userSession;

    public function setUp()
    {
        parent::setUp();

        $this->userSession = $this->container->get('UserSession');
    }

    public function testCreateTokenForRegisteredUser()
    {
        $user = $this->application->getContainer()->get('App\Dumps\UserDump')->make();

        $jwt = $this->userSession->createNewToken($user);

        $this->assertNotNull($jwt);
    }
}