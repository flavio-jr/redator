<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Dumps\UserDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Repositories\ApplicationRepository\OwnershipUpdate\ApplicationOwnershipTransfer;
use App\Exceptions\UserNotAllowedReceiveApplicationOwnershipTransfer;

class ApplicationOwnershipTransferTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationOwnershipTransfer
     */
    private $applicationOwnershipTransfer;

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

        $this->applicationOwnershipTransfer = $this->container->get(ApplicationOwnershipTransfer::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testApplicationOwnerMustBeCapableOfTransferOwnershipToOtherPartnerUser()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $otherPartner = $this->userDump->create(['type' => 'P']);

        Player::setPlayer($owner);

        $application = $this->applicationDump->create(['owner' => $owner]);

        $applicationWithNewOwner = $this->applicationOwnershipTransfer
            ->transferOwnerShip($application->getSlug(), $otherPartner->getUsername());

        $this->assertEquals($otherPartner->getUsername(), $applicationWithNewOwner->getAppOwner()->getUsername());
    }

    public function testWritterUserCantReceiveApplicationOwnership()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($owner);

        $application = $this->applicationDump->create(['owner' => $owner]);

        $this->expectException(UserNotAllowedReceiveApplicationOwnershipTransfer::class);

        $this->applicationOwnershipTransfer
            ->transferOwnerShip($application->getSlug(), $writter->getUsername());
    }
}