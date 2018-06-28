<?php

namespace App\Repositories\UserMasterRepository\Query;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\User;
use App\Exceptions\EntityNotFoundException;

final class UserMasterQuery implements UserMasterQueryInterface
{
    /**
     * The user entity repository
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(User::class);
    }

    public function getMasterUser(): User
    {
        $master = $this->repository
            ->findOneBy(['type' => 'M']);

        if (!$master) {
            throw new EntityNotFoundException('User(master)');
        }

        return $master;
    }
}