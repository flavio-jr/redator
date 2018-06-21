<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Query\ApplicationMasterQuery;
use Tests\DatabaseRefreshTable;
use App\Entities\Application;
use App\Exceptions\EntityNotFoundException;

class ApplicationMasterQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var ApplicationMasterQuery
     */
    private $applicationMasterQuery;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationMasterQuery = $this->container->get(ApplicationMasterQuery::class);
    }

    public function testMustGetApplicationBySlug()
    {
        $application = $this->applicationDump->create();

        $applicationGetted = $this->applicationMasterQuery
            ->getApplication($application->getSlug());

        $this->assertInstanceOf(Application::class, $applicationGetted);
    }

    public function testNonExistentApplicationMustThrownException()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->applicationMasterQuery
            ->getApplication('death-star');
    }
}