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

    public function testUpdateUserMustBeTheSameAsTheEnv()
    {
        $userMaster = $this->userMasterDump->create();
        $pass = strrev(getenv('USER_DEFAULT_PASSWORD'));

        putenv("USER_DEFAULT_PASSWORD={$pass}");
        
        $data = $this->userMasterDump
            ->make()
            ->toArray();

        $updatedUserMaster = $this->userMasterUpdate
            ->update($data);

        $this->assertTrue(password_verify(getenv('USER_DEFAULT_PASSWORD'), $updatedUserMaster->getPassword()));
    }
}