<?php

namespace App\Controllers\PublicationsController;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class PublicationDestructionControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testMustReturnHttpOkForDeletePublication()
    {
        $publication = $this->publicationDump->create();
        $application = $publication->getApplication();

        Player::setPlayer($application->getAppOwner());

        $response = $this->delete(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications/{$publication->getSlug()}");

        $this->assertEquals(200, $response->getStatusCode());
    }
}