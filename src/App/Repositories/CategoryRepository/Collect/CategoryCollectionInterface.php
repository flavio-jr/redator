<?php

namespace App\Repositories\CategoryRepository\Collect;

interface CategoryCollectionInterface
{
    /**
     * Retrieves an list of categories
     * @method getAll
     * @param array $filters
     * @return array
     */
    public function getAll(array $filters = []): array;
}