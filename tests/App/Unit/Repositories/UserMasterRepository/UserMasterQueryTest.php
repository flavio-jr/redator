<?php

namespace Tests\App\Unit\Repositories\UserMasterRepository;

use Tests\TestCase;
use App\Entities\User;
use App\Services\Persister\PersisterInterface as Persister;
use App\Repositories\UserMasterRepository\Query\UserMasterQuery;
use App\Exceptions\EntityNotFoundException;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserMasterDump;

class UserMasterQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserMasterDump
     */
    private $userMasterDump;

    /**
     * @var UserMasterQuery
     */
    private $userMasterQuery;

    public function setUp()
    {
        parent::setUp();

        $this->userMasterDump = $this->container->get(UserMasterDump::class);
        $this->userMasterQuery = $this->container->get(UserMasterQuery::class);
    }

    public function testShouldNotFindUserMaster()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->userMasterQuery
            ->getMasterUser();
    }

    public function testShouldFindTheMasterUser()
    {
        $this->userMasterDump->create();

        $user = $this->userMasterQuery
            ->getMasterUser();

        $this->assertNotNull($user);
    }
}