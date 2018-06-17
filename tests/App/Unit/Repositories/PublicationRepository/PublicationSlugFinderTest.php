<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Finder\PublicationSlugFinder;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;

class PublicationSlugFinderTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationSlugFinder
     */
    private $publicationSlugFinder;

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

        $this->publicationSlugFinder = $this->container->get(PublicationSlugFinder::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testFindPublicationBySlugMustBeNotNull()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        $publication = $this->publicationDump->create(['application' => $application]);

        Player::setPlayer($owner);

        $publicationFinded = $this->publicationSlugFinder
            ->find($publication->getSlug(), $publication->getApplication()->getSlug());

        $this->assertNotNull($publicationFinded);
    }
}