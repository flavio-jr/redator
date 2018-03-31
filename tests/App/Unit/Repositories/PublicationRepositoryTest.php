<?php

namespace Tests\App\Unit\Repositories;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

class PublicationRepositoryTest extends TestCase
{
    use DatabaseRefreshTable;

    private $publicationRepository;
    private $publicationDump;
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationRepository = $this->container->get('PublicationRepository');
        $this->publicationDump = $this->container->get('App\Dumps\PublicationDump');
        $this->userDump = $this->container->get('App\Dumps\UserDump');
    }

    public function testCreateNewPublication()
    {
        $publication = $this->publicationDump->make();

        $appId = $publication->getApplication()->getId();
        $categoryId = $publication->getCategory()->getId();

        $data = $publication->toArray();
        $data['application'] = $appId;
        $data['category'] = $categoryId;

        $publicationCreated = $this->publicationRepository->create($data);

        $this->assertDatabaseHave($publicationCreated);
    }

    public function testUpdatePublication()
    {
        $publication = $this->publicationDump->create();

        $appOwner = $publication->getApplication()->getAppOwner();

        Player::setPlayer($appOwner);

        $newPublication = $this->publicationDump->make()->toArray();
        $newPublication['category'] = $newPublication['category']->getId();

        $publicationUpdated = $this->publicationRepository->update($publication->getId(), $newPublication);

        $this->assertTrue($publicationUpdated);
    }

    public function testDestroyPublication()
    {
        $publication = $this->publicationDump->create();

        $publicationOwner = $publication->getApplication()->getAppOwner();

        Player::setPlayer($publicationOwner);

        $publicationDeleted = $this->publicationRepository->destroy($publication->getId());

        $this->assertTrue($publicationDeleted);
    }

    public function testUpdatePublicationThatBelongsToOtherUser()
    {
        $publication = $this->publicationDump->create();
        $otherUser = $this->userDump->create();

        Player::setPlayer($otherUser);

        $newPublication = $this->publicationDump->make()->toArray();
        $newPublication['category'] = $newPublication['category']->getId();

        $publicationUpdated = $this->publicationRepository->update($publication->getId(), $newPublication);

        $this->assertFalse($publicationUpdated);
    }

    public function testDestroyPublicationThatBelongsToOtherUser()
    {
        $publication = $this->publicationDump->create();

        $user = $this->userDump->create();

        Player::setPlayer($user);

        $publicationDeleted = $this->publicationRepository->destroy($publication->getId());

        $this->assertFalse($publicationDeleted);   
    }
}