<?php

namespace App\Querys\Categories;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Category;
use Doctrine\ORM\QueryBuilder;

final class CategoriesQuery implements CategoriesQueryInterface
{
    /**
     * The category entity query builder
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(EntityManagerInterface $em)
    {
        $this->queryBuilder = $em
            ->getRepository(Category::class)
            ->createQueryBuilder('c');
    }

    public function get(array $filters = []): array
    {
        if (isset($filters['name']) && !empty($filters['name'])) {
            $name = mb_strtolower($filters['name']);

            $this->queryBuilder
                ->where('LOWER(c.name) LIKE :name')
                ->setParameter('name', "%{$name}%");
        }

        $maxPerPage = config()['app']['max_page_count'];

        return $this->queryBuilder
            ->orderBy('c.name')
            ->setFirstResult($filters['page'] ?? 0)
            ->getQuery()
            ->setMaxResults($maxPerPage)
            ->getArrayResult();
    }
}