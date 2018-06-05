<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Store\ApplicationStore;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

class ApplicationStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var ApplicationStore
     */
    private $applicationStore;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationStore = $this->container->get(ApplicationStore::class);
    }

    public function testStoreApplication()
    {
        $appData = $this->applicationDump->make();

        Player::setPlayer($appData->getAppOwner());

        $application = $this->applicationStore->store($appData->toArray());

        $this->assertNotNull($application);
    }
}