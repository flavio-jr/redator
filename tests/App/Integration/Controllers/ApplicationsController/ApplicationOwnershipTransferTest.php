<?php

namespace Tests\App\Integration\Controllers\ApplicationsController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Application;

class ApplicationOwnershipTransferTest extends TestCase
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

    public function testTransferOwnerShipMustReturnHttpOk()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $newOwner = $this->userDump->create(['type' => 'P']);

        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $response = $this->patch(
            Application::PREFIX . "/users/apps/{$application->getSlug()}",
            [
                'new_owner' => $newOwner->getUsername()
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAttemptTransferOwnershipToWritterUserMustReturnHttpForbidden()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $response = $this->patch(
            Application::PREFIX . "/users/apps/{$application->getSlug()}",
            [
                'new_owner' => $writter->getUsername()
            ]
        );

        $this->assertEquals(403, $response->getStatusCode());
    }
}