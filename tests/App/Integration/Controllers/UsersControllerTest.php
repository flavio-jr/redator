<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Application;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Services\Player;

class UsersControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get('App\Dumps\UserDump');
        $this->applicationDump = $this->container->get('App\Dumps\ApplicationDump');
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testRegisterNewUser()
    {
        $userData = $this->userDump
            ->make()
            ->toArray();

        $response = $this->post(Application::PREFIX . '/users', $userData);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegisterUserWithoutRequiredData()
    {
        $userData = $this->userDump
            ->make()
            ->toArray();

        unset($userData['name']);

        $response = $this->post(Application::PREFIX . '/users', $userData);

        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testRegisterUserWithExistentUsername()
    {
        $user = $this->userDump->create();

        $userData = $this->userDump
            ->make(['username' => $user->getUserName()])
            ->toArray();
        
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

    public function testUpdateUserWithoutRequiredData()
    {
        $user = $this->userDump->create();

        $userDataUpdate = $this->userDump->make(['username' => $user->getUserName()])->toArray();
        unset($userDataUpdate['name']);

        $response = $this->put(Application::PREFIX . "/users/{$user->getId()}", $userDataUpdate);

        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testUpdateUsernameToAlreadyTakenOne()
    {
        $user = $this->userDump->create();
        $userToUpdate = $this->userDump->create();

        $updateData = $this->userDump->make(['username' => $user->getUserName()])->toArray();

        $response = $this->put(Application::PREFIX . "/users/{$userToUpdate->getId()}", $updateData);

        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testCheckForUsernameAvailability()
    {
        $username = $this->userDump->make()->getUsername();

        $response = $this->get(Application::PREFIX . "/users/username-availaibility/{$username}");

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetUserApps()
    {
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $applications = $this->dumpFactory->produce($this->applicationDump, 5, ['owner' => $user]);

        $response = $this->get(Application::PREFIX . "/users/apps");

        $this->assertEquals(200, $response->getStatusCode());
    }
}