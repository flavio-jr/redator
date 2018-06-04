<?php

namespace App\Services\Persister;

use App\Database\EntityInterface;

interface PersisterInterface
{
    /**
     * Persists an entity on the database
     * @method persist
     * @param EntityInterface $entity
     */
    public function persist(EntityInterface $entity);

    /**
     * Remove an entity from the database
     * @method remove
     * @param EntityInterface
     */
    public function remove(EntityInterface $entity);
}