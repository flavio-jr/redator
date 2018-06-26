<?php

namespace Tests\App\Integration\Controllers\ApplicationsController;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Dumps\UserDump;
use App\Application;
use App\Services\Player;

class AppGetControllerTest extends TestCase
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

    public function testGetAppMustReturnHttpOk()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $partner]);

        Player::setPlayer($partner);

        $response = $this->get(Application::PREFIX . "/users/apps/{$application->getSlug()}");

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetAppMustReturnHttpNotFoundForApplicationThatDoesntBelongsToUser()
    {
        $user = $this->userDump->create();
        $application = $this->applicationDump->create();

        Player::setPlayer($user);

        $response = $this->get(Application::PREFIX . "/users/apps/{$application->getSlug()}");

        $this->assertEquals(404, $response->getStatusCode());
    }
}