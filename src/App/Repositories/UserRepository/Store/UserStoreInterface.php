<?php

namespace App\Repositories\UserRepository\Store;

use App\Entities\User;

interface UserStoreInterface
{
    public function store(array $data): User;
}