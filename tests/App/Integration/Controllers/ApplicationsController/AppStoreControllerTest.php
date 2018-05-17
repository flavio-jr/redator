<?php

namespace Tests\App\Integration\Controllers\ApplicationsController;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Application;

class AppStoreControllerTest extends TestCase
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

    public function testStoreAppMustReturnHttpOk()
    {
        Player::setPlayer($this->userDump->create());

        $appData = $this->applicationDump->make()->toArray();

        $response = $this->post(Application::PREFIX . '/users/apps', $appData);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStoreAppWithMissingDataMustReturnPreConditionFailed()
    {
        Player::setPlayer($this->userDump->create());

        $appData = $this->applicationDump->make()->toArray();

        unset($appData['name']);

        $response = $this->post(Application::PREFIX . '/users/apps', $appData);

        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testStoreAppWithExistentNameMustReturnPreConditionFailed()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($this->userDump->create());

        $appData = $this->applicationDump->make(['name' => $application->getName()])->toArray();

        $response = $this->post(Application::PREFIX . '/users/apps', $appData);

        $this->assertEquals(412, $response->getStatusCode());
    }
}