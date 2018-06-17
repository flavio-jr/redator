<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Store\PublicationStore;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;

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

        $this->publicationStore = $this->container->get(PublicationStore::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testStorePublicationMustPersistOnDatabase()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $user]);

        $publicationData = $this->publicationDump->make(['application' => $application]);

        Player::setPlayer($user);
        
        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publication = $this->publicationStore->store($application->getSlug(), $data);

        $this->assertDatabaseHave($publication);
    }

    public function testStorePublicationWithUnexistentApplicationMustReturnNull()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $user]);

        $publicationData = $this->publicationDump->make(['application' => $application]);

        Player::setPlayer($user);
        
        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publication = $this->publicationStore->store(strrev($application->getSlug()), $data);

        $this->assertNull($publication);
    }

    public function testStorePublicationWithUnexistentCategoryMustReturnNull()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $user]);

        $publicationData = $this->publicationDump->make(['application' => $application]);

        Player::setPlayer($user);
        
        $data = $publicationData->toArray();
        $data['category'] = strrev($publicationData->getCategory()->getSlug());

        $publication = $this->publicationStore->store($application->getSlug(), $data);

        $this->assertNull($publication);
    }
}