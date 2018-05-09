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
    private $dumpFactory;
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get('App\Dumps\ApplicationDump');
        $this->dumpFactory = $this->container->get('DumpFactory');
        $this->userDump = $this->container->get('App\Dumps\UserDump');
    }

    public function testMustReturnHttpOkForStoreApp()
    {
        $appData = $this->applicationDump->make();

        Player::setPlayer($appData->getAppOwner());

        $response = $this->post(Application::PREFIX . '/applications', $appData->toArray());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNotRegisterApplicationWithMissingData()
    {
        $appData = $this->applicationDump->make();

        Player::setPlayer($appData->getAppOwner());

        $data = $appData->toArray();
        unset($data['name']);

        $response = $this->post(Application::PREFIX . '/applications', $data);

        $this->assertEquals(412, $response->getStatusCode());
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

    public function testMustNotUpdateWithMissingData()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $updateData = $this->applicationDump
            ->make(['owner' => $application->getAppOwner()])
            ->toArray();

        unset($updateData['name']);

        $response = $this->put(Application::PREFIX . "/applications/{$application->getId()}", $updateData);

        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForDestroyApp()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $response = $this->delete(Application::PREFIX . "/applications/{$application->getId()}");

        $this->assertEquals(200, $response->getStatusCode());
    }
}