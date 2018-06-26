<?php

namespace App\Repositories\CategoryRepository\Collect;

use App\Querys\Categories\CategoriesQueryInterface as CategoryQuery;

final class CategoryCollection implements CategoryCollectionInterface
{
    /**
     * @var CategoryQuery
     */
    private $categoryQuery;

    public function __construct(CategoryQuery $categoryQuery)
    {
        $this->categoryQuery = $categoryQuery;
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $filters = []): array
    {
        return $this->categoryQuery
            ->get($filters);
    }
}
