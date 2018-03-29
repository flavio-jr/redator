<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use App\Entities\Application;
use App\Services\Player;

class ApplicationRepository
{
    private $repository;
    private $persister;

    public function __construct(EntityManager $em, Persister $persister)
    {
        $this->repository = $em->getRepository('App\Entities\Application');
        $this->persister = $persister;
    }

    public function create(array $data)
    {
        $application = new Application();

        $data['owner'] = Player::user();

        $application->fromArray($data);

        $this->persister->persist($application);

        return $application;
    }
}