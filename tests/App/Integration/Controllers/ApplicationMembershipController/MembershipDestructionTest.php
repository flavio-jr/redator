<?php

namespace Tests\App\Integration\Controllers\ApplicationMembershipController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class MembershipDestructionTest extends TestCase
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

    public function testDestroyMemberShipMustReturnHttpOk()
    {
        $partnerUser = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        $application = $this->applicationDump->create(['owner' => $partnerUser, 'team' => [$writter]]);

        Player::setPlayer($partnerUser);

        $response = $this->delete(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/membership/{$writter->getUsername()}"
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUnauthorizedUserMustReceiveHttpForbidden()
    {
        $partnerUser = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();
        $otherWritter = $this->userDump->create();

        $application = $this->applicationDump->create(['owner' => $partnerUser, 'team' => [$writter, $otherWritter]]);

        Player::setPlayer($otherWritter);

        $response = $this->delete(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/membership/{$writter->getUsername()}"
        );

        $this->assertEquals(403, $response->getStatusCode());
    }
}