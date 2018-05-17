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

    public function testMustReturnHttpOkForDestroyApp()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $response = $this->delete(Application::PREFIX . "/applications/{$application->getId()}");

        $this->assertEquals(200, $response->getStatusCode());
    }
}