<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Repositories\PublicationRepository\Destruction\PublicationDestruction;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;

class PublicationDestructionTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationDestruction
     */
    private $publicationDestruction;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    /**
     * @var UserDump
     */
    private $userDump;
    
    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationDestruction = $this->container->get(PublicationDestruction::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testDestroyPublicationMustBeSuccessful()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        $publication = $this->publicationDump->create(['application' => $application]);

        Player::setPlayer($owner);

        $publicationDeleted = $this->publicationDestruction
            ->destroy(
                $publication->getSlug(),
                $publication->getApplication()->getSlug()
            );

        $this->assertTrue($publicationDeleted);
    }

    public function testDestroyPublicationWithUnexistentAplicationShouldNotBeSuccessful()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        $publication = $this->publicationDump->create(['application' => $application]);

        Player::setPlayer($owner);

        $publicationDeleted = $this->publicationDestruction
            ->destroy(
                $publication->getSlug(),
                strrev($publication->getApplication()->getSlug())
            );

        $this->assertFalse($publicationDeleted);
    }
}