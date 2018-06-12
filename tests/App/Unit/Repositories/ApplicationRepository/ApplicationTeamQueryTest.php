<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Repositories\ApplicationRepository\Query\ApplicationTeamQuery;
use Tests\DatabaseRefreshTable;
use App\Entities\Application;
use Doctrine\ORM\NoResultException;

class ApplicationTeamQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;
    
    /**
     * @var ApplicationTeamQuery
     */
    private $applicationTeamQuery;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationTeamQuery = $this->container->get(ApplicationTeamQuery::class);
    }

    public function testGetUserApplications()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        $application = $this->applicationDump->create(['owner' => $owner, 'team' => [$writter]]);

        Player::setPlayer($writter);

        $applicationsWithWritter = $this->applicationTeamQuery
            ->getUserApplications();

        $this->assertCount(1, $applicationsWithWritter);
    }

    public function testGetApplicationWithTeamMustReturnApplication()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        $application = $this->applicationDump->create(['owner' => $owner, 'team' => [$writter]]);

        Player::setPlayer($writter);

        $applicationFinded = $this->applicationTeamQuery
            ->getApplication($application->getSlug());

        $this->assertInstanceOf(Application::class, $applicationFinded);
    }

    public function testGetApplicationFromUserThatAreNotInTeamMustThrownException()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($writter);

        $this->expectException(NoResultException::class);

        $applicationFinded = $this->applicationTeamQuery
            ->getApplication($application->getSlug());
    }
}