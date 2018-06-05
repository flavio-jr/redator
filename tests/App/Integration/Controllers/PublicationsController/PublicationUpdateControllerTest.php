<?php

namespace Tests\App\Integration\Controllers\PublicationsController;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class PublicationUpdateControllerTest extends TestCase
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

    public function testUpdatePublicationMustReturnHttpCreated()
    {
        $publication = $this->publicationDump->create();
        $application = $publication->getApplication();

        Player::setPlayer($application->getAppOwner());

        $data = $this->publicationDump
            ->make()
            ->toArray();
        
        $data['category'] = $data['category']->getSlug();

        $response = $this->put(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications/{$publication->getSlug()}", $data);

        $this->assertEquals(201, $response->getStatusCode());
    }
}