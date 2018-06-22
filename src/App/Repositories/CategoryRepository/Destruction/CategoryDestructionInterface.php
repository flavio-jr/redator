<?php

namespace App\Repositories\CategoryRepository\Destruction;

interface CategoryDestructionInterface
{
    /**
     * Destroys an category
     * @method destroy
     * @param string $category
     */
    public function destroy(string $category);
}