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