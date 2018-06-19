<?php

namespace Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;

trait DatabaseRefreshTable
{
    public function setUpDatabase()
    {
        $em = $this->container->get($this->config['app']['orm'])->getEntityManager();
        $em->getConnection()->beginTransaction();
    }

    public function dropDatabase()
    {
        $em = $this->container->get($this->config['app']['orm'])->getEntityManager();
        $em->getConnection()->rollBack();
    }
}