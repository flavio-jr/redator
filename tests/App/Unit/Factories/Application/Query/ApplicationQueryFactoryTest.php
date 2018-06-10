<?php

namespace Tests\App\Unit\Factories\Application\Query;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;
use App\Factorys\Application\Query\ApplicationQueryFactory;
use App\Services\Player;
use App\Repositories\ApplicationRepository\Query\ApplicationMasterQuery;
use App\Repositories\ApplicationRepository\Query\ApplicationQuery;

class ApplicationQueryFactoryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationQueryFactory
     */
    private $applicationQueryFactory;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationQueryFactory = $this->container->get(ApplicationQueryFactory::class);
    }

    public function testGetApplicationMustReturnMasterQuery()
    {
        Player::setPlayer($this->userDump->create(['type' => 'M']));

        $applicationQuery = $this->applicationQueryFactory
            ->getApplicationQuery();

        $this->assertInstanceOf(ApplicationMasterQuery::class, $applicationQuery);
    }

    public function testGetApplicationMustReturnQuery()
    {
        Player::setPlayer($this->userDump->create(['type' => 'P']));

        $applicationQuery = $this->applicationQueryFactory
            ->getApplicationQuery();

        $this->assertInstanceOf(ApplicationQuery::class, $applicationQuery);
    }
}