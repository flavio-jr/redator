<?php

namespace Tests\App\Integration\Controllers\ApplicationsController;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Dumps\UserDump;
use App\Services\Player;
use App\Application;

class UserAppsControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testGetUserAppsMustReturnHttpOk()
    {
        $user = $this->userDump->create();
        
        $this->dumpFactory->produce($this->applicationDump, 5, ['owner' => $user]);

        Player::setPlayer($user);

        $response = $this->get(Application::PREFIX . '/users/apps');

        $this->assertEquals(200, $response->getStatusCode());
    }
}