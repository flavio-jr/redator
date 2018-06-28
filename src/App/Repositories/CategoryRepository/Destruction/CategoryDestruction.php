<?php

namespace App\Repositories\CategoryRepository\Destruction;

use App\Repositories\CategoryRepository\Query\CategoryQueryInterface as CategoryQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Services\Player;
use App\Exceptions\UserNotAllowedException;

final class CategoryDestruction implements CategoryDestructionInterface
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

    public function destroy(string $category)
    {
        if (Player::user()->isWritter()) {
            throw new UserNotAllowedException();
        }

        $category = $this->categoryQuery
            ->getCategoryByName($category);

        $this->persister->remove($category);
    }
}