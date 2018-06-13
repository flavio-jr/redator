<?php

namespace Tests\App\Unit\Repositories\ApplicationTeamRepository;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Repositories\ApplicationTeamRepository\Destruction\ApplicationMemberDestruction;
use Tests\DatabaseRefreshTable;
use App\Exceptions\UserNotAllowedToRemoveMemberFromApplication;

class ApplicationTeamDestructionTest extends TestCase
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

    /**
     * @var ApplicationMemberDestruction
     */
    private $applicationMemberDestruction;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationMemberDestruction = $this->container->get(ApplicationMemberDestruction::class);
    }

    public function testOnlyAppOwnerCanRemoveUserFromTeam()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($partner);

        $application = $this->applicationDump->create(['owner' => $partner, 'team' => [$writter]]);

        $applicationWithoutWritter = $this->applicationMemberDestruction
            ->destroy($writter->getUsername(), $application->getSlug());

        $this->assertCount(0, $applicationWithoutWritter->getTeam());
    }

    public function testPartnerUserMustNotBeCapableOfRemovingUserFromThirdPartieApplication()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $otherPartner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($otherPartner);

        $application = $this->applicationDump->create(['owner' => $partner, 'team' => [$writter]]);

        $this->expectException(UserNotAllowedToRemoveMemberFromApplication::class);

        $this->applicationMemberDestruction
            ->destroy($writter->getUsername(), $application->getSlug());
    }

    public function testWritterUserMustBeCapableOfRemoveHimselfFromApplicationTeam()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($writter);

        $application = $this->applicationDump->create(['owner' => $partner, 'team' => [$writter]]);

        $applicationWithoutWritter = $this->applicationMemberDestruction
            ->destroy($writter->getUsername(), $application->getSlug());

        $this->assertCount(0, $applicationWithoutWritter->getTeam());
    }

    public function testWritterUserMustNotBeCapableOfRemoveOtherWritterFromApplication()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();
        $otherWritter = $this->userDump->create();

        Player::setPlayer($otherWritter);

        $application = $this->applicationDump->create(['owner' => $partner, 'team' => [$writter, $otherWritter]]);

        $this->expectException(UserNotAllowedToRemoveMemberFromApplication::class);

        $this->applicationMemberDestruction
            ->destroy($writter->getUsername(), $application->getSlug());
    }

    public function testMasterUserMustBeCapableOfRemoveAnyWritter()
    {
        $master = $this->userDump->create(['type' => 'M']);
        $partner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($master);

        $application = $this->applicationDump->create(['owner' => $partner, 'team' => [$writter]]);

        $applicationWithoutWritter = $this->applicationMemberDestruction
            ->destroy($writter->getUsername(), $application->getSlug());

        $this->assertCount(0, $applicationWithoutWritter->getTeam());
    }
}