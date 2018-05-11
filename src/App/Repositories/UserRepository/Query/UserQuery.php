<?php

namespace App\Repositories\UserRepository\Query;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class UserQuery implements UserQueryInterface
{
    /**
     * The user repository
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * @inheritdoc
     */
    public function findByUsername(string $username): ?User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function isUsernameAvailable(string $username): bool
    {
        return is_null($this->findByUsername($username));
    }
}