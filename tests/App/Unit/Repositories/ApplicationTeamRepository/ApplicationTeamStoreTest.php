<?php

namespace Tests\App\Unit\Repositories\ApplicationTeamRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Repositories\ApplicationTeamRepository\Store\ApplicationTeamStore;
use App\Exceptions\UserNotAllowedToAddMemberToApplication;

class ApplicationTeamStoreTest extends TestCase
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

    /**
     * @var ApplicationTeamStore
     */
    private $applicationTeamStore;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationTeamStore = $this->container->get(ApplicationTeamStore::class);
    }

    public function testStoreWritterUserInApplicationTeam()
    {
        $partnerUser = $this->userDump->create(['type' => 'P']);
        $writterUser = $this->userDump->create();

        Player::setPlayer($partnerUser);

        $application = $this->applicationDump->create(['owner' => $partnerUser]);

        $application = $this->applicationTeamStore
            ->store($writterUser->getUsername(), $application->getSlug());

        $this->assertCount(1, $application->getTeam());
    }

    public function testStoreUserMemberWithAWritterLoggedUser()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $loggedUser = $this->userDump->create();
        $newMember = $this->userDump->create();

        Player::setPlayer($loggedUser);

        $application = $this->applicationDump->create(['owner' => $partner]);

        $this->expectException(UserNotAllowedToAddMemberToApplication::class);

        $this->applicationTeamStore
            ->store($newMember->getUsername(), $application->getSlug());
    }

    public function testStoreUserMemberWithMasterLoggedUser()
    {
        $master = $this->userDump->create(['type' => 'M']);
        $newMember = $this->userDump->create();

        Player::setPlayer($master);

        $application = $this->applicationDump->create(['owner' => $master]);

        $applicationWithTeam = $this->applicationTeamStore
            ->store($newMember->getUsername(), $application->getSlug());

        $this->assertCount(1, $applicationWithTeam->getTeam());
    }
}