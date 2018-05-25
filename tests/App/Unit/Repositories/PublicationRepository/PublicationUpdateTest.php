<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Update\PublicationUpdate;

class PublicationUpdateTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationUpdate
     */
    private $publicationUpdate;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationUpdate = $this->container->get(PublicationUpdate::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testUpdatePublicationMustBeSuccessful()
    {
        $publication = $this->publicationDump->create();

        $data = $this->publicationDump->make();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $updateData = $data->toArray();
        $updateData['category'] = $data->getCategory()->getSlug();

        $publicationUpdated = $this->publicationUpdate
            ->update(
                $publication->getSlug(),
                $publication->getApplication()->getSlug(),
                $updateData
            );

        $this->assertTrue($publicationUpdated);
    }

    public function testUpdatePublicationWithUnexistentApplicationMustReturnFalse()
    {
        $publication = $this->publicationDump->create();

        $data = $this->publicationDump->make();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $updateData = $data->toArray();
        $updateData['category'] = $data->getCategory()->getSlug();

        $publicationUpdated = $this->publicationUpdate
            ->update(
                $publication->getSlug(),
                strrev($publication->getApplication()->getSlug()),
                $updateData
            );

        $this->assertFalse($publicationUpdated);
    }

    public function testUpdatePublicationWithUnexistentCategoryMustReturnFalse()
    {
        $publication = $this->publicationDump->create();

        $data = $this->publicationDump->make();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $updateData = $data->toArray();
        $updateData['category'] = strrev($data->getCategory()->getSlug());

        $publicationUpdated = $this->publicationUpdate
            ->update(
                $publication->getSlug(),
                strrev($publication->getApplication()->getSlug()),
                $updateData
            );

        $this->assertFalse($publicationUpdated);
    }
}