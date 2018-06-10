<?php

namespace App\Repositories\UserMasterRepository\Store;

use App\Entities\User;

interface UserMasterStoreInterface
{
    /**
     * Stores the master user if him does not exist
     * @method store
     * @return void
     */
    public function store(): User;
}