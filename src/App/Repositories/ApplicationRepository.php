<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Services\Persister;
use App\Entities\Application;
use App\Services\Player;
use App\Exceptions\EntityNotFoundException;
use App\Database\Types\ApplicationType;

class ApplicationRepository
{
    /**
     * The application entity
     * @var Application
     */
    private $application;

    /**
     * The application repository
     * @var EntityRepository
     */
    private $repository;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        Application $application,
        EntityManager $em,
        Persister $persister
    ) {
        $this->application = $application;
        $this->repository = $em->getRepository('App\Entities\Application');
        $this->persister = $persister;
    }

    /**
     * Search an application by id
     * @method find
     * @param string $id
     * @return mixed
     */
    public function find(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Stores an new application
     * @method create
     * @param array $data
     * @return Application
     */
    public function create(array $data): Application
    {
        $application = $this->application;

        $data['owner'] = Player::user();

        $application->fromArray($data);

        $this->persister->persist($application);

        return $application;
    }

    /**
     * Update an app data
     * @method update
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        $application = $this->repository->find($id);

        if (!$application) {
            throw new EntityNotFoundException('App\Entities\Application');
        }

        if (!$this->appBelongsToUser($application)) {
            return false;
        }

        $application->fromArray($data);

        $this->persister->persist($application);

        return true;
    }

    /**
     * Destroy an application
     * @method destroy
     * @param string $id
     * @return bool
     */
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

    /**
     * Check if an app belongs to user
     * @param Application $application
     * @return bool
     */
    public function appBelongsToUser(Application $application): bool
    {
        return Player::user()->getId() === $application->getAppOwner()->getId();
    }

    /**
     * Get all user applications
     * @method getApplicationsByUser
     * @return mixed
     */
    public function getApplicationsByUser()
    {
        $user = Player::user();

        if (!$user) {
            return false;
        }

        $applications = $this->repository->findBy(['owner' => $user->getId()]);

        return array_map(function ($app) {
            $data = $app->toArray();

            $data['id'] = $app->getId();

            $type = $data['type'];
            $data['type'] = [$type => ApplicationType::getApplicationTypes()[$type]];

            unset($data['owner']);

            return $data;
        }, $applications);
    }
}