<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Update\ApplicationUpdate;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

class ApplicationUpdateTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var ApplicationUpdate
     */
    private $applicationUpdate;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationUpdate = $this->container->get(ApplicationUpdate::class);
    }

    public function testShouldUpdateExistentApp()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $appData = $this->applicationDump->make()->toArray();

        $appUpdated = $this->applicationUpdate->update($application->getSlug(), $appData);

        $this->assertTrue($appUpdated);
    }

    public function testShouldNotUpdateUnexistentApp()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $appData = $this->applicationDump->make()->toArray();

        $appNotUpdated = $this->applicationUpdate->update(strrev($application->getSlug()), $appData);

        $this->assertFalse($appNotUpdated);
    }
}