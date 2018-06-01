<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Dumps\DumpsFactories\DumpFactory;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Repositories\PublicationRepository\Collect\PublicationCollection;

class PublicationSearchTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationCollection
     */
    private $publicationCollection;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->publicationCollection = $this->container->get(PublicationCollection::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testSearhForPublicationsShouldNotBeEmpty()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $this->dumpFactory
            ->produce(
                $this->publicationDump,
                5,
                ['application' => $application]
            );

        $publications = $this->publicationCollection
            ->get($application->getSlug());

        $this->assertCount(5, $publications);
    }
}