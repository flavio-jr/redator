<?php

namespace App\Repositories\CategoryRepository\Query;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\Category;

final class CategoryQuery implements CategoryQueryInterface
{
    /**
     * The repository for category
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Category::class);
    }

    public function getCategoryByName(string $category): ?Category
    {
        return $this->repository->findOneBy(['name' => $category]);
    }
}