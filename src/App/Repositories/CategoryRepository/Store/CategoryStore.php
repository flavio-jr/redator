<?php

namespace App\Repositories\CategoryRepository\Store;

use App\Entities\Category;
use App\Services\Persister;

final class CategoryStore implements CategoryStoreInterface
{
    /**
     * The category entity
     * @var Category
     */
    private $category;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        Category $category,
        Persister $persister
    )
    {
        $this->category = $category;
        $this->persister = $persister;
    }

    public function store(array $data): Category
    {
        $this->category->fromArray($data);

        $this->persister->persist($this->category);

        return $this->category;
    }
}