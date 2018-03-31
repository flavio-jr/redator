<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use App\Application;
use Tests\DatabaseRefreshTable;
use App\Repositories\PublicationRepository;
use App\Dumps\PublicationDump;
use App\Services\Player;

class PublicationsControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * The repository for publications
     * @var PublicationRepository
     */
    private $publicationRepository;
    
    /**
     * The publication dump
     * @var PublicationDump 
     */
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

    public function testMustReturnHttpOkForUpdatePublication()
    {
        $publication = $this->publicationDump->create();

        $publicationData = $this->publicationDump->make([
            'application' => $publication->getApplication()
        ])->toArray();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $publicationData['category'] = $publicationData['category']->getId();

        $response = $this->put(Application::PREFIX . "/publications/{$publication->getId()}", $publicationData);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpOkForDeletePublication()
    {
        $publication = $this->publicationDump->create();

        Player::setPlayer($publication->getApplication()->getAppOwner());

        $response = $this->delete(Application::PREFIX . "/publications/{$publication->getId()}");

        $this->assertEquals(200, $response->getStatusCode());
    }
}