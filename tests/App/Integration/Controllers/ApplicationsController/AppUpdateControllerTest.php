<?php

namespace Tests\App\Integration\Controllers\ApplicationsController;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;

class AppUpdateControllerTest extends TestCase
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

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testUpdateAppMustReturnHttpOk()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $response = $this->put(Application::PREFIX . "/users/apps/{$application->getSlug()}", $application->toArray());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateAppMustReturnHttpNotFound()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($this->userDump->create(['type' => 'P']));

        $response = $this->put(Application::PREFIX . "/users/apps/{$application->getSlug()}", $application->toArray());

        $this->assertEquals(404, $response->getStatusCode());
    }
}