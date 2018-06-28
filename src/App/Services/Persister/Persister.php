<?php

namespace App\Services\Persister;

use Doctrine\ORM\EntityManager;
use App\Database\EntityInterface;
use App\Services\Persister\PersisterInterface;

class Persister implements PersisterInterface
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
     * @inheritdoc
     */
    public function persist(EntityInterface $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function remove(EntityInterface $entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}