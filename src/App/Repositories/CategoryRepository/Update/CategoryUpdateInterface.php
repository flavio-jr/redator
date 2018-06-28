<?php

namespace App\Repositories\CategoryRepository\Update;

interface CategoryUpdateInterface
{
    /**
     * Updates an category
     * @method update
     * @param string $category
     * @param array $data
     */
    public function update(string $category, array $data);
}