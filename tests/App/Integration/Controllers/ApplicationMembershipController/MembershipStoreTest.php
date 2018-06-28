<?php

namespace Tests\App\Integration\Controllers\ApplicationMembershipController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class MembershipStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testStoreNewMemberIntoApplicationMustReturnHttpOk()
    {
        $userPartner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();
        $application = $this->applicationDump->create(['owner' => $userPartner]);

        Player::setPlayer($userPartner);

        $response = $this->post(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/membership",
            [
                'member' => $writter->getUsername()
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUnauthorizedUserMustGetHttpForbidden()
    {
        $userPartner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();
        $application = $this->applicationDump->create(['owner' => $userPartner]);

        Player::setPlayer($writter);

        $response = $this->post(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/membership",
            [
                'member' => $writter->getUsername()
            ]
        );

        $this->assertEquals(403, $response->getStatusCode());   
    }
}