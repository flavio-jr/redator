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
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $response = $this->put(Application::PREFIX . "/users/apps/{$application->getSlug()}", $application->toArray());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateAppMustReturnHttpForbidden()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($this->userDump->create());

        $response = $this->put(Application::PREFIX . "/users/apps/{$application->getSlug()}", $application->toArray());

        $this->assertEquals(403, $response->getStatusCode());
    }
}