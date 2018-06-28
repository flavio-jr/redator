<?php

namespace App\Repositories\CategoryRepository\Update;

use App\Repositories\CategoryRepository\Query\CategoryQueryInterface as CategoryQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Services\Player;
use App\Exceptions\UserNotAllowedException;

final class CategoryUpdate implements CategoryUpdateInterface
{
    /**
     * The category query repository
     * @var CategoryQuery
     */
    private $categoryQuery;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        CategoryQuery $categoryQuery,
        Persister $persister
    ) {
        $this->categoryQuery = $categoryQuery;
        $this->persister = $persister;
    }

    public function update(string $category, array $data)
    {
        if (Player::user()->isWritter()) {
            throw new UserNotAllowedException();
        }

        $category = $this->categoryQuery
            ->getCategoryByName($category);

        $category->fromArray($data);

        $this->persister->persist($category);
    }
}