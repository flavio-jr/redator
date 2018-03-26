<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Application;

class UsersControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get('App\Dumps\UserDump');
    }

    public function testRegisterNewUser()
    {
        $userData = $this->userDump
            ->make()
            ->toArray();

        $response = $this->post(Application::PREFIX . '/users', $userData);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegisterUserWithExistentUsername()
    {
        $userData = $this->userDump
            ->make()
            ->toArray();
        
        $firstResponse = $this->post(Application::PREFIX . '/users', $userData);
        $response = $this->post(Application::PREFIX . '/users', $userData);

        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testUpdateUser()
    {
        $user = $this->userDump->create();

        $userDataUpdate = $this->userDump->make(['username' => $user->getUserName()])->toArray();
        
        $response = $this->put(Application::PREFIX . "/users/{$user->getId()}", $userDataUpdate);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateUsernameToAlreadyTakenOne()
    {
        $user = $this->userDump->create();
        $userToUpdate = $this->userDump->create();

        $updateData = $this->userDump->make(['username' => $user->getUserName()])->toArray();

        $response = $this->put(Application::PREFIX . "/users/{$userToUpdate->getId()}", $updateData);

        $this->assertEquals(412, $response->getStatusCode());
    }
}