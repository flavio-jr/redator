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
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testMustReturnHttpOkForGetPublications()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(Application::PREFIX . "/users/apps/{$application->getSlug()}/publications");

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithTitleQueryParam()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            "title={$publications[0]->getTitle()}"
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithMinDateQueryParam()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'min_date=' . Carbon::now()->format('d/m/Y')
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithMaxDateQueryParam()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'max_date=' . Carbon::now()->format('d/m/Y')
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithCategoryQueryParam()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'category=' . $publications[0]->getCategory()->getSlug()
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForGetPublicationsWithPageQueryParam()
    {
        $application = $this->applicationDump->create();

        Player::setPlayer($application->getAppOwner());

        $publications = $this->dumpFactory->produce($this->publicationDump, 5, ['application' => $application]);

        $response = $this->get(
            Application::PREFIX . "/users/apps/{$application->getSlug()}/publications",
            'page=1'
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
}