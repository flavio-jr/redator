<?php

namespace Tests\App\Integration\Controllers\PublicationsController;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\PublicationDump;
use App\Services\Player;
use App\Application;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;

class PublicationStoreControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationDump;
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

        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
    }

    public function testShouldReturnHttpCreatedForStoreNewPublication()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $user]);

        $publication = $this->publicationDump->make(['application' => $application]);
        
        Player::setPlayer($user);

        $data = $publication->toArray();
        $data['category'] = $publication->getCategory()
            ->getSlug();

        $response = $this->post(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications", $data);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testMustReturnHttpForbiddenForUnauthorizedUser()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $otherUser = $this->userDump->create(['type' => 'P']);

        $application = $this->applicationDump->create(['owner' => $user]);

        $publication = $this->publicationDump->make(['application' => $application]);
        
        Player::setPlayer($otherUser);

        $data = $publication->toArray();
        $data['category'] = $publication->getCategory()
            ->getSlug();

        $response = $this->post(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications", $data);

        $this->assertEquals(403, $response->getStatusCode());
    }
}