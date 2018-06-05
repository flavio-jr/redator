<?php

namespace App\Repositories\UserRepository\Query;

use App\Entities\User;
use App\Exceptions\EntityNotFoundException;

interface UserQueryInterface
{
    /**
     * Find an user by username
     * @method findByUsername
     * @param string $username
     * @return User
     * @throws EntityNotFoundException
     */
    public function findByUsername(string $username): User;

    /**
     * Check for username availability
     * @method isUsernameAvailable
     * @param string $username
     * @return bool
     */
    public function isUsernameAvailable(string $username): bool;
}