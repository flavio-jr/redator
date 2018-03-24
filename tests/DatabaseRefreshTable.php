<?php

namespace Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;

trait DatabaseRefreshTable
{
    public function setUpDatabase()
    {
        $em = self::$application->getContainer()->get(self::$config['app']['orm'])->getEntityManager();

        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($this->getMetaClass($em));
    }

    public function dropDatabase()
    {
        $em = self::$application->getContainer()->get(self::$config['app']['orm'])->getEntityManager();

        $schemaTool = new SchemaTool($em);

        $schemaTool->dropSchema($this->getMetaClass($em));
    }

    private function getMetaClass(EntityManager $em)
    {
        $entities = array_diff(scandir(self::$config['app']['path'] . '/Entities'), ['.', '..']);
        $entitiesNamespace = '\App\Entities\\';

        $metaClasses = array();

        foreach ($entities as $entity) {
            $metaClasses[] = $em->getClassMetadata($entitiesNamespace . explode('.', $entity)[0]);
        }

        return $metaClasses;
    }
}