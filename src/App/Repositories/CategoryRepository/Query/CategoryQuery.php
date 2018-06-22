<?php

namespace App\Repositories\CategoryRepository\Query;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\Category;
use App\Exceptions\EntityNotFoundException;

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
        $category = $this->repository->findOneBy(['slug' => $category]);

        if (!$category) {
            throw new EntityNotFoundException('Category');
        }

        return $category;
    }
}