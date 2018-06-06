<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Repositories\UserMasterRepository\Store\UserMasterStore;

class UserMasterStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserMasterStore
     */
    private $userMasterStore;

    public function setUp()
    {
        parent::setUp();

        $this->userMasterStore = $this->container->get(UserMasterStore::class);
    }

    public function testMustCreateUserWithMasterLevel()
    {
        $userMaster = $this->userMasterStore
            ->store();

        $this->assertEquals('M', $userMaster->getType());
    }
}