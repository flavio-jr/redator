<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;
use App\Repositories\UserRepository\Update\UserUpdate;
use App\Services\Player;

class UserUpdateTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * The user dump
     * @var UserDump
     */
    private $userDump;

    /**
     * The user update
     * @var UserUpdate
     */
    private $userUpdate;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->userUpdate = $this->container->get(UserUpdate::class);
    }

    public function testUpdateUser()
    {
        $pass = 'potter';

        $user = $this->userDump->create(['password' => $pass]);

        Player::setPlayer($user);

        $data = $this->userDump->make()->toArray();
        $data['password'] = $pass;

        $userUpdated = $this->userUpdate->update($data);

        $this->assertTrue($userUpdated);
    }
}