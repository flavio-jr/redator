<?php

namespace App\Repositories\UserMasterRepository\Update;

use App\Entities\User;

interface UserMasterUpdateInterface
{
    /**
     * Updates the password of the master user
     * @method update
     * @return User
     */
    public function update(array $data): User;
}