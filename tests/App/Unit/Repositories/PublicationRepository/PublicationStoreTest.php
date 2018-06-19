<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Store\PublicationStore;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UserNotAllowedToWritePublication;

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

    public function testStorePublicationWithUnexistentCategoryMustThrowException()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $user]);

        $publicationData = $this->publicationDump->make(['application' => $application]);

        Player::setPlayer($user);
        
        $data = $publicationData->toArray();
        $data['category'] = strrev($publicationData->getCategory()->getSlug());

        $this->expectException(EntityNotFoundException::class);

        $this->publicationStore->store($application->getSlug(), $data);
    }

    public function testWritterUserMustBeCapableOfStorePublication()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($writter);

        $application = $this->applicationDump->create(['owner' => $owner, 'team' => [$writter]]);
        $publicationData = $this->publicationDump->make(['application' => $application]);

        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publication = $this->publicationStore->store($application->getSlug(), $data);

        $this->assertDatabaseHave($publication);
    }

    public function testMasterUserMustBeCapableOfWritePublicationInAnyApp()
    {
        $master = $this->userDump->create(['type' => 'M']);

        $publication = $this->publicationDump->create();

        Player::setPlayer($master);

        $publicationData = $this->publicationDump->make();

        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publication = $this->publicationStore->store($publication->getApplication()->getSlug(), $data);

        $this->assertDatabaseHave($publication);
    }

    public function testWritterNotInApplicationTeamMustNotBeAbleToStorePublication()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($writter);

        $application = $this->applicationDump->create(['owner' => $owner]);
        $publicationData = $this->publicationDump->make(['application' => $application]);

        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $this->expectException(EntityNotFoundException::class);

        $publication = $this->publicationStore->store($application->getSlug(), $data);
    }
}