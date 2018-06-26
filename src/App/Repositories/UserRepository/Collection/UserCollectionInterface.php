<?php

namespace App\Repositories\UserRepository\Collection;

interface UserCollectionInterface
{
    /**
     * Retrieve all the registered users
     * @method getAll
     * @return array
     */
    public function getAll(): array;
}