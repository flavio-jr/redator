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
        $pass = 'obi-wan';

        $user = $this->userDump->create(['password' => $pass]);
        $data = $this->userDump->make()->toArray();
        $data['password'] = $pass;

        Player::setPlayer($user);

        $response = $this->put(Application::PREFIX . '/users', $data);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateUserWithExistentUserNameMustReturnPreconditionFailed()
    {
        $pass = 'obi-wan';

        $someUser = $this->userDump->create(['password' => $pass]);
        $currentUser = $this->userDump->create(['password' => $pass]); 

        $data = $this->userDump->make(['username' => $someUser->getUsername()])->toArray();
        $data['password'] = $pass;

        Player::setPlayer($currentUser);

        $response = $this->put(Application::PREFIX . '/users', $data);

        $this->assertEquals(412, $response->getStatusCode());
    }
}