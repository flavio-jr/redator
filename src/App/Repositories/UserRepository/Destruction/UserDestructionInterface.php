<?php

namespace App\Repositories\UserRepository\Destruction;

interface UserDestructionInterface
{
    /**
     * Destroy an user
     * @method destroy
     * @param string $username
     */
    public function destroy(string $username);
}