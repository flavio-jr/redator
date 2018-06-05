<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Query\ApplicationQuery;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;
use App\Dumps\DumpsFactories\DumpFactory;

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

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationQuery = $this->container->get(ApplicationQuery::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testShouldFindApplication()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $applicationFind = $this->applicationQuery->getApplication($application->getSlug());

        $this->assertNotNull($applicationFind);
    }

    public function testCantFindApplicationThatBelongsToOtherUser()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($this->userDump->create());

        $applicationNotFound = $this->applicationQuery->getApplication($application->getSlug());

        $this->assertNull($applicationNotFound);
    }

    public function testGetAllAplicationsOfCurrentUser()
    {
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $this->dumpFactory->produce($this->applicationDump, 5, ['owner' => $user]);

        $userApps = $this->applicationQuery->getUserApplications();

        $this->assertCount(5, $userApps);
    }
}