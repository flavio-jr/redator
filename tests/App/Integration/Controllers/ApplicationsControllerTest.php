<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class ApplicationsControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    private $applicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get('App\Dumps\ApplicationDump');
    }

    public function testMustReturnHttpOkForStoreApp()
    {
        $appData = $this->applicationDump->make();

        Player::setPlayer($appData->getAppOwner());

        $response = $this->post(Application::PREFIX . '/applications', $appData->toArray());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForUpdateApp()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $updateData = $this->applicationDump
            ->make(['owner' => $application->getAppOwner()])
            ->toArray();

        $response = $this->put(Application::PREFIX . "/applications/{$application->getId()}", $updateData);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForDestroyApp()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $response = $this->delete(Application::PREFIX . "/applications/{$application->getId()}");

        $this->assertEquals(200, $response->getStatusCode());
    }
}