<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class UserRepository
{
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository('App\Entities\User');
    }
}