<?php

namespace Tests\App\Integration\Controllers\UsersController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Application;

class UserStateControllerTest extends TestCase
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

    public function testUpdateUserStateMustReturnHttpOk()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $userTarget = $this->userDump->create();

        Player::setPlayer($user);

        $reponse = $this->patch(
            Application::PREFIX . "/users/{$userTarget->getUsername()}",
            ['status' => 0]
        );

        $this->assertEquals(200, $reponse->getStatusCode());
    }

    public function testUpdateUserStateWithNotAllowedUserMustReturnHttpForbidden()
    {
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $reponse = $this->patch(
            Application::PREFIX . "/users/anakin",
            ['status' => 0]
        );

        $this->assertEquals(403, $reponse->getStatusCode());
    }
}
