<?php

namespace App\Repositories\ApplicationRepository\Store;

use App\Entities\Application;
use App\Services\Persister;
use App\Services\Player;

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

    public function __construct(
        Application $application,
        Persister $persister
    )
    {
        $this->application = $application;
        $this->persister = $persister;
    }

    /**
     * @inheritdoc
     */
    public function store(array $data): Application
    {
        $data['owner'] = Player::user();

        $this->application->fromArray($data);

        $this->persister->persist($this->application);

        return $this->application;
    }
}