<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Services\Persister;
use App\Entities\Category;

class CategoryRepository
{
    /**
     * The category entity
     * @var Category
     */
    private $category;

    /**
     * The category repository
     * @var EntityRepository
     */
    private $repository;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        Category $category,
        EntityManager $em,
        Persister $persister
    ) {
        $this->category = $category;
        $this->repository = $em->getRepository('App\Entities\Category');
        $this->persister = $persister;
    }

    /**
     * Searchs category by id
     * @method find
     * @param string $id
     * @return mixed
     */
    public function find(string $id): mixed
    {
        return $this->repository->find($id);
    }

    /**
     * Creates a new Category
     * @method create
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        $category = $this->category;

        $category->fromArray($data);

        $this->persister->persist($category);

        return $category;
    }
}