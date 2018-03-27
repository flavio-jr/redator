<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use App\Entities\User;
use App\Exceptions\UniqueFieldException;
use App\Exceptions\EntityNotFoundException;

class UserRepository
{
    private $repository;
    private $persister;

    public function __construct(EntityManager $em, Persister $persister)
    {
        $this->repository = $em->getRepository('App\Entities\User');
        $this->persister = $persister;
    }

    public function create(array $data)
    {
        if ($this->repository->findOneBy(['username' => $data['username']])) {
            throw new UniqueFieldException('username');
        }

        $user = new User();
        $user->fromArray($data);

        $this->persister->persist($user);

        return $user;
    }

    public function getUserByCredentials(string $username, string $password)
    {
        $users = $this->repository->findBy(['username' => $username]);

        if (!count($users)) {
            return null;
        }

        $user = $users[0];
        
        if (password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    public function find(string $id): User
    {
        return $this->repository->find($id);
    }

    public function update(string $id, array $data): User
    {
        $user = $this->find($id);

        if (!$user) {
            throw new EntityNotFoundException('App\Entities\User');
        }

        if (isset($data['username'])) {
            $qb = $this->repository
                ->createQueryBuilder('u')
                ->andWhere('u.username <> :currentUsername')
                ->setParameter('currentUsername', $user->getUsername())
                ->andWhere('u.username = :newUsername')
                ->setParameter('newUsername', $data['username'])
                ->getQuery();

            $userNameExists = $qb->setMaxResults(1)->getOneOrNullResult();

            if ($userNameExists) {
                throw new UniqueFieldException('username');    
            }
        } 

        $user->setName($data['name'] ?? $user->getName());
        $user->setUsername($data['username'] ?? $user->getUsername());

        $this->persister->persist($user);

        return $user;
    }

    private function findByUsername(string $username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function isUsernameAvailable(string $username)
    {
        return is_null($this->repository->findOneBy(['username' => $username]));
    }
}