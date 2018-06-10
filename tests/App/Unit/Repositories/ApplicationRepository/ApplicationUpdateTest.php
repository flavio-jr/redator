<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Update\ApplicationUpdate;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Dumps\UserDump;

class ApplicationUpdateTest extends TestCase
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
     * @var ApplicationUpdate
     */
    private $applicationUpdate;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationUpdate = $this->container->get(ApplicationUpdate::class);
    }

    public function testShouldUpdateExistentApp()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $appData = $this->applicationDump->make()->toArray();

        $appUpdated = $this->applicationUpdate->update($application->getSlug(), $appData);

        $this->assertTrue($appUpdated);
    }

    public function testShouldNotUpdateUnexistentApp()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $appData = $this->applicationDump->make()->toArray();

        $appNotUpdated = $this->applicationUpdate->update(strrev($application->getSlug()), $appData);

        $this->assertFalse($appNotUpdated);
    }
}