<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use App\Repositories\UserRepository\Store\UserStore;
use App\Dumps\UserDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

class UserStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * The user store repository
     * @var UserStore
     */
    private $userStore;

    /**
     * The user dump
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userStore = $this->container->get(UserStore::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testUserCreation()
    {
        $pass = 'snow';

        $userData = $this->userDump
            ->make(['password' => $pass])
            ->toArray();
        
        $userData['password'] = $pass;

        $user = $this->userStore->store($userData);

        $this->assertNotNull($user);
    }

    public function testUserCreationWithoutLoggedUserMustDisableUserCreated()
    {
        $pass = 'snow';

        $userData = $this->userDump
            ->make(['password' => $pass])
            ->toArray();
        
        $userData['password'] = $pass;

        $user = $this->userStore->store($userData);

        $this->assertFalse($user->isEnabled());
    }

    public function testUserCreationWithLoggedUserMustKeepUserEnabled()
    {
        Player::setPlayer($this->userDump->create());

        $pass = 'snow';

        $userData = $this->userDump
            ->make(['password' => $pass])
            ->toArray();
        
        $userData['password'] = $pass;

        $user = $this->userStore->store($userData);

        $this->assertTrue($user->isEnabled());
    }
}