<?php

namespace Tests\App\Integration\Controllers\UsersController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class UserUpdateControllerTest extends TestCase
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

    public function testUpdateUserMustReturnHttpOk()
    {
        $user = $this->userDump->create();
        $data = $this->userDump->make()->toArray();

        Player::setPlayer($user);

        $response = $this->put(Application::PREFIX . '/users', $data);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateUserWithExistentUserNameMustReturnPreconditionFailed()
    {
        $someUser = $this->userDump->create();
        $currentUser = $this->userDump->create(); 

        $data = $this->userDump->make(['username' => $someUser->getUsername()])->toArray();

        Player::setPlayer($currentUser);

        $response = $this->put(Application::PREFIX . '/users', $data);

        $this->assertEquals(412, $response->getStatusCode());
    }
}