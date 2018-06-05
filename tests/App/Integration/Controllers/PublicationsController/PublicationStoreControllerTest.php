<?php

namespace Tests\App\Integration\Controllers\PublicationsController;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\PublicationDump;
use App\Services\Player;
use App\Application;

class PublicationStoreControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationDump;
     */
    private $publicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testShouldReturnHttpCreatedForStoreNewPublication()
    {
        $publication = $this->publicationDump->make();
        $application = $publication->getApplication();
        
        Player::setPlayer($application->getAppOwner());

        $data = $publication->toArray();
        $data['category'] = $publication->getCategory()
            ->getSlug();

        $response = $this->post(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications", $data);

        $this->assertEquals(201, $response->getStatusCode());
    }
}