<?php

namespace App\Repositories\UserRepository\Update;

interface UserUpdateInterface
{
    public function update(string $id, array $data): bool;
}