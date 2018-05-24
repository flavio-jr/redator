<?php

namespace App\Repositories\CategoryRepository\Query;

use App\Entities\Category;

interface CategoryQueryInterface
{
    /**
     * Finds an category by its name
     * @method getCategoryByName
     * @param string $category
     * @return Category
     */
    public function getCategoryByName(string $category): ?Category;
}