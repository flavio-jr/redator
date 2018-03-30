<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use App\Services\Persister;
use App\Entities\Category;

class CategoryRepository
{
    private $repository;
    private $persister;

    public function __construct(EntityManager $em, Persister $persister)
    {
        $this->repository = $em->getRepository('App\Entities\Category');
        $this->persister = $persister;
    }

    public function create(array $data): Category
    {
        $category = new Category();

        $category->fromArray($data);

        $this->persister->persist($category);

        return $category;
    }
}