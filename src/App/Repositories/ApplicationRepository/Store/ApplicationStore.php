<?php

namespace App\Repositories\ApplicationRepository\Store;

use App\Entities\Application;
use App\Services\Persister;
use App\Services\Player;
use App\Services\Slugify\SlugifyInterface as Slugify;

final class ApplicationStore implements ApplicationStoreInterface
{
    /**
     * The application entity
     * @var Application
     */
    private $application;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The slugifier service
     * @var Slugify
     */
    private $slugifier;

    public function __construct(
        Application $application,
        Persister $persister,
        Slugify $slugifier
    )
    {
        $this->application = $application;
        $this->persister = $persister;
        $this->slugifier = $slugifier;
    }

    /**
     * @inheritdoc
     */
    public function store(array $data): Application
    {
        $data['owner'] = Player::user();
        $data['slug'] = $this->slugifier->slugify($data['name']);

        $this->application->fromArray($data);

        $this->persister->persist($this->application);

        return $this->application;
    }
}