<?php

namespace App\Repositories\ApplicationRepository\Store;

use App\Entities\Application;
use App\Services\Persister;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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

    /**
     * The repository for BD operations
     * @var EntityRepository
     */
    private $repository;

    public function __construct(
        Application $application,
        Persister $persister,
        EntityManager $em
    )
    {
        $this->application = $application;
        $this->persister = $persister;
        $this->repository = $em->getRepository(Application::class);
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