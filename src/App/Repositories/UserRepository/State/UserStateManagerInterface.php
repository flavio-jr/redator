<?php

namespace App\Repositories\UserRepository\State;

interface UserStateManagerInterface
{
    /**
     * Change the user status (enable or disabled)
     * @method changeStatus
     * @param string $username
     * @param bool $newState
     */
    public function changeStatus(string $username, bool $newState);
}