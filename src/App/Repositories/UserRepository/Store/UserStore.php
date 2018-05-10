<?php

namespace App\Repositories\UserRepository\Store;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use Doctrine\ORM\EntityRepository;

final class UserStore implements UserStoreInterface
{
    /**
     * The user entity
     * @var User
     */
    private $user;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The user repository
     * @var EntityRepository
     */
    private $repository;

    public function __construct(
        User $user,
        EntityManager $em,
        Persister $persister
    )
    {
        $this->user = $user;
        $this->persister = $persister;
        $this->repository = $em->getRepository(User::class);
    }

    public function store(array $data): User
    {
        $this->user->fromArray($data);

        $this->persister->persist($this->user);

        return $this->user;
    }
}