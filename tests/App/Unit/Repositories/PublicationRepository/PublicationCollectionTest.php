<?php

namespace Tests\App\Unit\Repositories\PublicationRepository;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Dumps\DumpsFactories\DumpFactory;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Repositories\PublicationRepository\Collect\PublicationCollection;
use App\Dumps\UserDump;

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
     * @var UserDump
     */
    private $userDump;

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
        $this->userDump = $this->container->get(UserDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testSearhForPublicationsShouldNotBeEmpty()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

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