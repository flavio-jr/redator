<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Store\PublicationStore;
use App\Services\Player;

class PublicationStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationStore
     */
    private $publicationStore;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationStore = $this->container->get(PublicationStore::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testStorePublicationMustPersistOnDatabase()
    {
        $publicationData = $this->publicationDump->make();
        $application = $publicationData->getApplication()->getSlug();

        Player::setPlayer($publicationData->getApplication()->getAppOwner());
        
        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publication = $this->publicationStore->store($application, $data);

        $this->assertDatabaseHave($publication);
    }

    public function testStorePublicationWithUnexistentApplicationMustReturnNull()
    {
        $publicationData = $this->publicationDump->make();
        $application = $publicationData->getApplication();

        Player::setPlayer($application->getAppOwner());
        
        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publication = $this->publicationStore->store(strrev($application->getSlug()), $data);

        $this->assertNull($publication);
    }

    public function testStorePublicationWithUnexistentCategoryMustReturnNull()
    {
        $publicationData = $this->publicationDump->make();
        $application = $publicationData->getApplication();

        Player::setPlayer($application->getAppOwner());
        
        $data = $publicationData->toArray();
        $data['category'] = strrev($publicationData->getCategory()->getSlug());

        $publication = $this->publicationStore->store($application->getSlug(), $data);

        $this->assertNull($publication);
    }
}