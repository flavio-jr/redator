<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Query\ApplicationQuery;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;

class ApplicationQueryTest extends TestCase
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
     * @var ApplicationQuery
     */
    private $applicationQuery;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationQuery = $this->container->get(ApplicationQuery::class);
    }

    public function testShouldFindApplication()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $applicationFind = $this->applicationQuery->getApplication($application->getName());

        $this->assertNotNull($applicationFind);
    }

    public function testCantFindApplicationThatBelongsToOtherUser()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($this->userDump->create());

        $applicationNotFound = $this->applicationQuery->getApplication($application->getName());

        $this->assertNull($applicationNotFound);
    }
}