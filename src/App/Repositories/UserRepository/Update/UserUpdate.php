<?php

namespace App\Repositories\UserRepository\Update;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use App\Services\Persister\PersisterInterface as Persister;
use Doctrine\ORM\EntityRepository;
use App\Services\Player;

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
     * @inheritdoc
     */
    public function update(array $data): bool
    {
        $user = Player::user();

        if (!$user) {
            return false;
        }

        $user->fromArray($data);

        $this->persister->persist($user);

        return true;
    }
}