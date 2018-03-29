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
}