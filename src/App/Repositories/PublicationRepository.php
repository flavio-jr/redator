<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use App\Services\Persister;

class PublicationRepository
{
    private $repository;
    private $persister;

    public function __construct(EntityManager $em, Persister $persister)
    {
        $this->repository = $em->getRepository('App\Entities\Publication');
        $this->persister = $persister;
    }
}