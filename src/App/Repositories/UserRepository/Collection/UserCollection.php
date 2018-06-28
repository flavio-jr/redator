<?php

namespace App\Repositories\UserRepository\Collection;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entities\User;
use App\Services\Player;
use App\Exceptions\UserNotAllowedException;

final class UserCollection implements UserCollectionInterface
{
    /**
     * The repository for the User entity
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(User::class);
    }

    public function getAll(): array
    {
        if (!Player::user()->isMaster()) {
            throw new UserNotAllowedException();
        }

        $results = $this->repository
            ->findAll();

        $filtered = array_filter($results, function (User $user) {
            return !$user->isMaster();
        });

        return array_map(function (User $user) {
            return [
                'username' => $user->getUsername(),
                'name'     => $user->getName()
            ];
        }, $filtered);
    }
}
