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

    public function find(string $id): Application
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Application
    {
        $application = new Application();

        $data['owner'] = Player::user();

        $application->fromArray($data);

        $this->persister->persist($application);

        return $application;
    }

    public function update(string $id, array $data): bool
    {
        $application = $this->repository->find($id);

        if (!$application) {
            throw new EntityNotFoundException('App\Entities\Application');
        }

        if (!$this->appBelongsToUser($application)) {
            return false;
        }

        $setterMap = Application::getSetterMap();

        foreach ($data as $colunmName => $value)
        {
            $setter = $setterMap[$colunmName];

            $application->{$setter}($value);
        }

        $this->persister->persist($application);

        return true;
    }

    public function destroy(string $id): bool
    {
        $application = $this->repository->find($id);

        if (!$application) {
            throw new EntityNotFoundException('App\Entities\Application');
        }

        if (!$this->appBelongsToUser($application)) {
            return false;
        }

        $this->persister->remove($application);

        return true;
    }

    public function appBelongsToUser(Application $application)
    {
        return Player::user()->getId() === $application->getAppOwner()->getId();
    }

    public function getApplicationsByUser()
    {
        $user = Player::user();

        if (!$user) {
            return false;
        }

        $applications = $this->repository->findBy(['owner' => $user->getId()]);

        return array_map(function ($app) {
            return $app->toArray();
        }, $applications);
    }
}