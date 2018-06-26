<?php

namespace App\Querys\Categories;

interface CategoriesQueryInterface
{
    /**
     * Does an query to get the categories
     * @method get
     * @param array $filters
     * @return array
     */
    public function get(array $filters = []): array;
}