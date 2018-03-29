<?php

namespace Tests\App\Unit\Repositories;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

class ApplicationRepositoryTest extends TestCase
{
    use DatabaseRefreshTable;

    private $applicationRepository;
    private $applicationDump;
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->applicationRepository = $this->container->get('ApplicationRepository');
        $this->applicationDump = $this->container->get('App\Dumps\ApplicationDump');
    }

    public function testCreateApplication()
    {
        $applicationData = $this->applicationDump->make();

        Player::setPlayer($applicationData->getAppOwner());

        $application = $this->applicationRepository->create($applicationData->toArray());

        $this->assertDatabaseHave($application);
    }
}