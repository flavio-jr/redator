<?php

namespace App\Repositories\UserRepository\Query;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Exceptions\EntityNotFoundException;

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
    public function findByUsername(string $username): User
    {
        $user = $this->repository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new EntityNotFoundException('User');
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function isUsernameAvailable(string $username): bool
    {
        return is_null($this->findByUsername($username));
    }
}