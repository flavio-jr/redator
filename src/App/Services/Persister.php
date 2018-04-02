<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Database\EntityInterface;

class Persister
{
    /**
     * The entity manager
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;        
    }

    /**
     * Persists an entity
     * @method persist
     * @param EntityInterface $entity
     */
    public function persist(EntityInterface $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * Remove an entity of the database
     * @method remove
     * @param EntityInterface
     */
    public function remove(EntityInterface $entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}