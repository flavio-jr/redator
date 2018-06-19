<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Store\ApplicationStore;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Dumps\UserDump;

class ApplicationStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationStore
     */
    private $applicationStore;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationStore = $this->container->get(ApplicationStore::class);
    }

    public function testStoreApplication()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $appData = $this->applicationDump->make(['owner' => $owner]);

        Player::setPlayer($appData->getAppOwner());

        $application = $this->applicationStore->store($appData->toArray());

        $this->assertNotNull($application);
    }
}