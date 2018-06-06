<?php

namespace App\Repositories\UserMasterRepository\Query;

use App\Entities\User;

interface UserMasterQueryInterface
{
    /**
     * Get the master user
     * @method getMasterUser
     * @return User
     */
    public function getMasterUser(): User;
}