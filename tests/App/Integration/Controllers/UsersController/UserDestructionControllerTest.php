<?php

namespace Tests\App\Integration\Controllers\UsersController;

use Tests\TestCase;
use App\Dumps\UserDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Application;

class UserDestructionTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testDestroyUserMustReturnHttpOkForMasterUser()
    {
        $master = $this->userDump->create(['type' => 'M']);
        $user = $this->userDump->create();

        Player::setPlayer($master);

        $response = $this->delete(Application::PREFIX . "/users/{$user->getUsername()}");

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDestroyMustReturnHttpForbiddenForNotMasterUser()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $user = $this->userDump->create();

        Player::setPlayer($partner);

        $response = $this->delete(Application::PREFIX . "/users/{$user->getUsername()}");

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDestroyMustReturnHttpNotFoundForUnexistentUser()
    {
        $partner = $this->userDump->create(['type' => 'P']);

        Player::setPlayer($partner);

        $username = strrev($partner->getUsername());

        $response = $this->delete(Application::PREFIX . "/users/{$username}");

        $this->assertEquals(403, $response->getStatusCode());
    }
}