<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Application;
use App\Dumps\UserDump;
use App\Services\Player;

class UsersControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
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

    public function testSendEmailToUnactiveUser()
    {
        $user = $this->userDump->create();
        $url = 'http://my.test.com/crazy/train';

        Player::setPlayer($user);

        $response = $this->post(Application::PREFIX . '/users/mailunactive', ['url' => $url]);

        if ($response->getStatusCode() !== 200) {
            file_put_contents('php://stderr', $response->getBody(), FILE_APPEND);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }
}