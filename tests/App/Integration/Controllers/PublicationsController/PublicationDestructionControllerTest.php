<?php

namespace App\Controllers\PublicationsController;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Dumps\UserDump;

class PublicationDestructionControllerTest extends TestCase
{
    use DatabaseRefreshTable;

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

    public function setUp()
    {
        parent::setUp();

        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testMustReturnHttpOkForDeletePublication()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        $publication = $this->publicationDump->create(['application' => $application]);

        Player::setPlayer($owner);

        $response = $this->delete(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications/{$publication->getSlug()}");

        $this->assertEquals(200, $response->getStatusCode());
    }
}