<?php

namespace App\Repositories\CategoryRepository\Store;

use App\Entities\Category;

interface CategoryStoreInterface
{
    /**
     * Stores a new category
     * @method store
     * @param array $data
     * @return Category
     */
    public function store(array $data): Category;
}