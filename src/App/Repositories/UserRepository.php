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
    ) {
        $this->user = $user;
        $this->repository = $em->getRepository(get_class($user));
        $this->persister = $persister;
    }

    /**
     * Creates new user
     * @method create
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $user = $this->user;
        $user->fromArray($data);

        $this->persister->persist($user);

        return $user;
    }

    /**
     * Return user if credentials are correct
     * @method getUserByCredentials
     * @param string $username
     * @param string $password
     * @return mixed
     */
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

    /**
     * Search user by id
     * @method find
     * @param string $id
     * @return User
     */
    public function find(string $id): User
    {
        return $this->repository->find($id);
    }

    /**
     * Updates user data
     * @method update
     * @param string $id
     * @param array $data
     * @return User
     */
    public function update(string $id, array $data): User
    {
        $user = $this->find($id);

        if (!$user) {
            throw new EntityNotFoundException('App\Entities\User');
        }

        $user->setName($data['name']);
        $user->setUsername($data['username']);

        $this->persister->persist($user);

        return $user;
    }

    /**
     * Search user by username
     * @method findByUsername
     * @param string $username
     * @return mixed
     */
    private function findByUsername(string $username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * Check for username availability
     * @param string $username
     * @return bool
     */
    public function isUsernameAvailable(string $username): bool
    {
        return is_null($this->repository->findOneBy(['username' => $username]));
    }
}