<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository\Finder\PublicationSlugFinder;
use App\Services\Player;

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

    public function setUp()
    {
        parent::setUp();

        $this->publicationSlugFinder = $this->container->get(PublicationSlugFinder::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testFindPublicationBySlugMustBeNotNull()
    {
        $publication = $this->publicationDump->create();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $publicationFinded = $this->publicationSlugFinder
            ->find($publication->getSlug(), $publication->getApplication()->getSlug());

        $this->assertNotNull($publicationFinded);
    }
}