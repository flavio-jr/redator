<?php

namespace Tests\App\Unit\Repositories\UserMasterRepository;

use Tests\TestCase;
use App\Dumps\UserMasterDump;
use App\Repositories\UserMasterRepository\Query\UserMasterQuery;
use App\Repositories\UserMasterRepository\Update\UserMasterUpdate;
use Tests\DatabaseRefreshTable;

class UserMasterUpdateTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserMasterUpdate
     */
    private $userMasterUpdate;

    /**
     * @var UserMasterDump
     */
    private $userMasterDump;

    public function setUp()
    {
        parent::setUp();

        $this->userMasterUpdate = $this->container->get(UserMasterUpdate::class);
        $this->userMasterDump = $this->container->get(UserMasterDump::class);
    }

    public function testUpdateUserMustChangeOnlyTheUserPassword()
    {
        $userMaster = $this->userMasterDump->create();
        $pass = strrev(getenv('USER_DEFAULT_PASSWORD'));

        putenv("USER_DEFAULT_PASSWORD={$pass}");

        $updatedUserMaster = $this->userMasterUpdate
            ->update();

        $this->assertEquals($userMaster->getUsername(), $updatedUserMaster->getUsername());
        $this->assertEquals($userMaster->getName(), $updatedUserMaster->getName());
    }
}