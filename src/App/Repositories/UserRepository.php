<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use App\Entities\User;
use App\Exceptions\UniqueFieldException;

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

    public function find(string $id)
    {
        return $this->repository->find($id);
    }

    public function findBy(array $where)
    {
        return $this->repository->findBy($where);
    }
}