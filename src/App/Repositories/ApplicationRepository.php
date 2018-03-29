<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use App\Entities\Application;
use App\Services\Player;
use App\Exceptions\EntityNotFoundException;

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

    public function update(string $id, array $data)
    {
        $application = $this->repository->find($id);

        if (!$application) {
            throw new EntityNotFoundException('App\Entities\Application');
        }

        $setterMap = Application::getSetterMap();

        foreach ($data as $colunmName => $value)
        {
            $setter = $setterMap[$colunmName];

            $application->{$setter}($value);
        }

        $this->persister->persist($application);

        return $application;
    }
}