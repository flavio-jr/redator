<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Repositories\UserRepository\Destruction\UserDestruction;
use App\Exceptions\UserNotAllowedToRemoveUsers;

class UserDestructionTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var UserDestruction
     */
    private $userDestruction;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->userDestruction = $this->container->get(UserDestruction::class);
    }

    public function testDestroyUserMustBeSuccessfulForMasterUser()
    {
        $master = $this->userDump->create(['type' => 'M']);
        $user = $this->userDump->create();
        $userId = $user->getId();

        Player::setPlayer($master);

        $this->userDestruction
            ->destroy($user->getUsername());

        $this->assertDatabaseDoenstHave($userId, $user);
    }

    public function testDestroyUserMustNotBeSuccessfulForPartnerUser()
    {
        $partner = $this->userDump->create(['type' => 'P']);
        $user = $this->userDump->create();

        Player::setPlayer($partner);

        $this->expectException(UserNotAllowedToRemoveUsers::class);

        $this->userDestruction
            ->destroy($user->getUsername());
    }

    public function testDestroyUserMustNotBeSuccessfulForWritterUser()
    {
        $writter = $this->userDump->create();

        $user = $this->userDump->create();

        Player::setPlayer($writter);

        $this->expectException(UserNotAllowedToRemoveUsers::class);

        $this->userDestruction
            ->destroy($user->getUsername());
    }
}