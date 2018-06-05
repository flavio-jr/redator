<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Repositories\PublicationRepository\Destruction\PublicationDestruction;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

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

    public function setUp()
    {
        parent::setUp();

        $this->publicationDestruction = $this->container->get(PublicationDestruction::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testDestroyPublicationMustBeSuccessful()
    {
        $publication = $this->publicationDump->create();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $publicationDeleted = $this->publicationDestruction
            ->destroy(
                $publication->getSlug(),
                $publication->getApplication()->getSlug()
            );

        $this->assertTrue($publicationDeleted);
    }

    public function testDestroyPublicationWithUnexistentAplicationShouldNotBeSuccessful()
    {
        $publication = $this->publicationDump->create();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $publicationDeleted = $this->publicationDestruction
            ->destroy(
                $publication->getSlug(),
                strrev($publication->getApplication()->getSlug())
            );

        $this->assertFalse($publicationDeleted);
    }
}