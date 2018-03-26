<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;

class Persister
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;        
    }

    public function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}