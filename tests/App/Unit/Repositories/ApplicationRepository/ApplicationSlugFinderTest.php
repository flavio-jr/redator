<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Finder\ApplicationSlugFinder;
use App\Entities\Application;
use App\Exceptions\EntityNotFoundException;

class ApplicationSlugFinderTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var ApplicationSlugFinder
     */
    private $applicationSlugFinder;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationSlugFinder = $this->container->get(ApplicationSlugFinder::class);
    }

    public function testFindApplicationMustBeInstanceOfApplication()
    {
        $application = $this->applicationDump->create();

        $applicationFinded = $this->applicationSlugFinder
            ->find($application->getSlug());

        return $this->assertInstanceOf(Application::class, $applicationFinded);
    }

    public function testFindUnexistentApplicationMustThrownException()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->applicationSlugFinder
            ->find('the-force-awakens');
    }
}