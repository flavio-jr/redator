<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Destruction\ApplicationDestruction;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Exceptions\EntityNotFoundException;

class ApplicationDestructionTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var ApplicationDestruction
     */
    private $applicationDestruction;

    /**
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->applicationDestruction = $this->container->get(ApplicationDestruction::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testShouldDeleteApplicationThatBelongsToCurrentUser()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $deleted = $this->applicationDestruction->destroy($application->getSlug());

        $this->assertTrue($deleted);
    }

    public function testShouldNotDeleteApplicationThatDoesntBelongsToCurrentUser()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create();

        Player::setPlayer($owner);

        $this->expectException(EntityNotFoundException::class);

        $this->applicationDestruction->destroy($application->getSlug()); 
    }
}