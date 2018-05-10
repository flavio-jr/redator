<?php

namespace App\Repositories\UserRepository\Update;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use Doctrine\ORM\EntityRepository;

final class UserUpdate implements UserUpdateInterface
{
    /**
     * The user entity
     * @var User
     */
    private $user;

    /**
     * The user repository
     * @var EntityRepository
     */
    private $repository;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        User $user,
        EntityManager $em,
        Persister $persister
    )
    {
        $this->user = $user;
        $this->repository = $em->getRepository(User::class);
        $this->persister = $persister;        
    }

    /**
     * Updates user data
     * @param string $id The user uuid
     * @param array $data The user new data
     * @return bool The result of the update operation
     */
    public function update(string $id, array $data): bool
    {
        $user = $this->repository->find($id);

        if (!$user) {
            return false;
        }

        $user->fromArray($data);

        $this->persister->persist($user);

        return true;
    }
}