<?php

namespace App\Repositories\UserRepository\Finder;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\User;
use App\Exceptions\EntityNotFoundException;
use Doctrine\DBAL\Types\ConversionException;

final class UserFinder implements UserFinderInterface
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

    public function find(string $identifier): User
    {
        try {
            $user = $this->repository
            ->find($identifier);
            
            if (!$user) {
                throw new EntityNotFoundException('User');
            }

            return $user;
        } catch (ConversionException $e) {
            throw new EntityNotFoundException('User');
        }
    }
}