<?php

namespace App\Repositories\UserRepository\Finder;

use App\Entities\User;
use App\Exceptions\EntityNotFoundException;

interface UserFinderInterface
{
    /**
     * Find a user based on the identifier
     * @method find
     * @param string $identifier
     * @return User
     * @throws EntityNotFoundException
     */
    public function find(string $identifier): User;
}