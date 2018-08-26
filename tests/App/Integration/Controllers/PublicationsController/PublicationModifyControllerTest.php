<?php

namespace Tests\App\Integration\Controllers\PublicationsController;

use Tests\TestCase;
use App\Dumps\PublicationDump;
use Tests\DatabaseRefreshTable;
use App\Application;
use App\Services\Player;

class PublicationModifyControllerTest extends TestCase
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

    public function testPatchPublicationMustGiveHttpNoContent()
    {
        $publication = $this
            ->publicationDump
            ->create(['application.override' => [
                'owner.override' => [
                    'type' => 'P'
                ]
            ]]);

        $application = $publication
            ->getApplication();

        $user = $application->getAppOwner();

        Player::setPlayer($user);

        $response = $this->patch(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications/{$publication->getSlug()}",
            ['status' => 'PB']
        );

        $this->assertEquals(204, $response->getStatusCode());
    }
}
