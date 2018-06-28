<?php

namespace App\Repositories\UserRepository\Update;

interface UserUpdateInterface
{
    /**
     * Updates user data
     * @param string $username The user username
     * @param array $data The user new data
     * @return bool The result of the update operation
     */
    public function update(array $data): bool;
}