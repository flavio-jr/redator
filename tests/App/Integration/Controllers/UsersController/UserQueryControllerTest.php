<?php

namespace Tests\App\Integration\Controllers\UsersController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Application;
use Tests\DatabaseRefreshTable;
use App\Controllers\UsersController\UserQueryController;
use App\Services\Player;

class UserQueryControllerTest extends TestCase
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

    public function testGetByUserNameMustReturnUser()
    {
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $response = $this->get(Application::PREFIX . '/users/' . $user->getUsername());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetByUsernameMustNotFoundUser()
    {
        $this->userDump->create();
        $fakeUserName = $this->userDump->make()->getUsername();

        $response = $this->get(Application::PREFIX . '/users/' . $fakeUserName);

        $this->assertEquals(404, $response->getStatusCode());
    }
}