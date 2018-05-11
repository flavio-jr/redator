<?php

namespace App\Repositories\UserRepository\Query;

use App\Entities\User;

interface UserQueryInterface
{
    /**
     * Find an user by username
     * @method findByUsername
     * @param string $username
     * @return User
     */
    public function findByUsername(string $username): ?User;

    /**
     * Check for username availability
     * @method isUsernameAvailable
     * @param string $username
     * @return bool
     */
    public function isUsernameAvailable(string $username): bool;
}