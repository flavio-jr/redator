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
    private $dumpFactory;
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->applicationRepository = $this->container->get('ApplicationRepository');
        $this->applicationDump = $this->container->get('App\Dumps\ApplicationDump');
        $this->dumpFactory = $this->container->get('DumpFactory');
        $this->userDump = $this->container->get('App\Dumps\UserDump');
    }

    public function testUpdateApplication()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $applicationData = $this->applicationDump
            ->make(['owner' => $application->getAppOwner()])
            ->toArray();

        $applicationUpdated = $this->applicationRepository->update($application->getId(), $applicationData);

        $this->assertTrue($applicationUpdated);
    }

    public function testUpdateApplicationThatDoesntBelongsToUser()
    {
        $application = $this->applicationDump->create();
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $applicationData = $this->applicationDump
            ->make(['owner' => $application->getAppOwner()])
            ->toArray();

        $applicationUpdate = $this->applicationRepository->update($application->getId(), $applicationData);

        $this->assertFalse($applicationUpdate);
    }

    public function testDestroyApplication()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $deleted = $this->applicationRepository->destroy($application->getId());

        $this->assertTrue($deleted);
    }

    public function testOnlyAppOwnerCanDestroy()
    {
        $application = $this->applicationDump->create();
        $otherUserNotAppOwner = $this->userDump->create();

        Player::setPlayer($otherUserNotAppOwner);

        $deleted = $this->applicationRepository->destroy($application->getId());

        $this->assertFalse($deleted);
    }

    public function testShouldReturnAllUserApps()
    {
        $user = $this->userDump->create();

        $this->dumpFactory->produce($this->applicationDump, 5, ['owner' => $user]);

        Player::setPlayer($user);

        $userApplications = $this->applicationRepository->getApplicationsByUser();

        $this->assertEquals(5, count($userApplications));
    }

    public function testShouldReturnFalseForNotLoggedUser()
    {
        $user = $this->userDump->create();

        $this->dumpFactory->produce($this->applicationDump, 5, ['owner' => $user]);

        Player::gameOver();

        $notFoundApps = $this->applicationRepository->getApplicationsByUser();

        $this->assertFalse($notFoundApps);
    }
}