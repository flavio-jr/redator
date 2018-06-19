<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Update\PublicationUpdate;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;

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

        $this->publicationUpdate = $this->container->get(PublicationUpdate::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testUpdatePublicationMustBeSuccessful()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        $publication = $this->publicationDump->create(['application' => $application]);

        $data = $this->publicationDump->make();

        Player::setPlayer($owner);

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

    public function testWritterUserMustNotBeCapableOfUpdatePublication()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $writter = $this->userDump->create();

        Player::setPlayer($writter);

        $application = $this->applicationDump->create(['owner' => $owner, 'team' => [$writter]]);
        $publication = $this->publicationDump->create(['application' => $application]);

        $publicationData = $this->publicationDump->make();

        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publicationUpdated = $this->publicationUpdate->update($publication->getSlug(), $application->getSlug(), $data);

        $this->assertFalse($publicationUpdated);
    }

    public function testMasterUserMustBeCapableOfUpdatePublicationInAnyApp()
    {
        $master = $this->userDump->create(['type' => 'M']);
        $application = $this->applicationDump->create();

        $publication = $this->publicationDump->create(['application' => $application]);

        Player::setPlayer($master);

        $publicationData = $this->publicationDump->make();

        $data = $publicationData->toArray();
        $data['category'] = $publicationData->getCategory()->getSlug();

        $publicationUpdated = $this->publicationUpdate->update($publication->getSlug(), $application->getSlug(), $data);

        $this->assertTrue($publicationUpdated);
    }
}