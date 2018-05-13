<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;
use App\Repositories\UserRepository\Update\UserUpdate;

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
        $user = $this->userDump->create();
        $data = $this->userDump->make()->toArray();

        $userUpdated = $this->userUpdate->update($data);

        $this->assertTrue($userUpdated);
    }
}