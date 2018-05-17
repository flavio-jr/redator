<?php

namespace Tests\App\Integration\Controllers\ApplicationsController;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class AppUpdateControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testUpdateAppMustReturnHttpOk()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $response = $this->put(Application::PREFIX . "/users/apps/{$application->getSlug()}", $application->toArray());

        $this->assertEquals(200, $response->getStatusCode());
    }
}