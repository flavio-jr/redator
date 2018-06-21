<?php

namespace Tests\App\Integration\Controllers\PublicationsController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Application;

class PublicationGetControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
    }

    public function testMustReturnHttpOkForGetPublication()
    {
        $writter = $this->userDump->create();
        $application = $this->applicationDump->create(['team' => [$writter]]);
        $publication = $this->publicationDump->create(['application' => $application]);

        Player::setPlayer($writter);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications/{$publication->getSlug()}"
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpNotFoundForGetUnexistentPublication()
    {
        $writter = $this->userDump->create();
        $application = $this->applicationDump->create(['team' => [$writter]]);

        Player::setPlayer($writter);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications/some-stranger-things"
        );

        $this->assertEquals(404, $response->getStatusCode());
    }
}