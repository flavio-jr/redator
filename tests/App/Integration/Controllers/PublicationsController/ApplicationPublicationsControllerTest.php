<?php

namespace Tests\App\Integration\Controllers\PublicationsController;

use Tests\TestCase;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Dumps\PublicationDump;
use App\Dumps\ApplicationDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;
use Carbon\Carbon;
use App\Dumps\UserDump;

class ApplicationPublicationsControllerTest extends TestCase
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

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testMustReturnHttpOkForGetPublications()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications");

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithTitleQueryParam()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            "title={$publications[0]->getTitle()}"
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithMinDateQueryParam()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'min_date=' . Carbon::now()->format('d/m/Y')
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithMaxDateQueryParam()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'max_date=' . Carbon::now()->format('d/m/Y')
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithCategoryQueryParam()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'category=' . $publications[0]->getCategory()->getSlug()
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithPageQueryParam()
    {
        $owner = $this->userDump->create(['type' => 'P']);
        $application = $this->applicationDump->create(['owner' => $owner]);

        Player::setPlayer($owner);

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'page=1'
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
}