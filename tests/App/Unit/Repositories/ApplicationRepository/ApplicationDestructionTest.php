<?php

namespace Tests\App\Unit\Repositories\ApplicationRepository;

use Tests\TestCase;
use App\Dumps\ApplicationDump;
use App\Repositories\ApplicationRepository\Destruction\ApplicationDestruction;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Dumps\UserDump;

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
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $deleted = $this->applicationDestruction->destroy($application->getName());

        $this->assertTrue($deleted);
    }

    public function testShouldNotDeleteApplicationThatDoesntBelongsToCurrentUser()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($this->userDump->create());

        $notDeleted = $this->applicationDestruction->destroy($application->getName());

        $this->assertFalse($notDeleted);
    }
}