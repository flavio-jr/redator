<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;

use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Repositories\UserRepository\State\UserStateManager;
use App\Exceptions\UserNotAllowedException;

class UserStateManagerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var UserStateManager
     */
    private $userStateManager;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->userStateManager = $this->container->get(UserStateManager::class);
    }

    public function testPartnerUserMustBeCapableOfEnableUser()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $unactiveUser = $this->userDump->create();

        Player::setPlayer($user);

        $this->userStateManager
            ->changeStatus($unactiveUser->getUsername(), true);

        $this->assertTrue(true); // No exception was thrown
    }

    public function testWritterUserMustNotBeCapableOfEnableUser()
    {
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $this->expectException(UserNotAllowedException::class);

        $this->userStateManager
            ->changeStatus('Yoda', true);
    }
}
