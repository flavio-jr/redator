<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use App\Application;
use Tests\DatabaseRefreshTable;

class PublicationsControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    private $publicationRepository;
    private $publicationDump;

    public function setUp()
    {
        parent::setUp();

        $this->publicationRepository = $this->container->get('PublicationRepository');
        $this->publicationDump = $this->container->get('App\Dumps\PublicationDump');
    }

    public function testMustReturnHttpOkForCreateNewPublication()
    {
        $publicationData = $this->publicationDump->make()->toArray();
        $publicationData['application'] = $publicationData['application']->getId();
        $publicationData['category'] = $publicationData['category']->getId();

        $response = $this->post(Application::PREFIX . '/publications', $publicationData);

        $this->assertEquals(200, $response->getStatusCode());
    }
}