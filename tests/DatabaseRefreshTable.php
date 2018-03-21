<?php

namespace Tests;

use Doctrine\ORM\Tools\SchemaTool;

trait DatabaseRefreshTable
{
    private $schemaTool;
    private $metaClasses;

    public function setUp()
    {
        parent::setUp();

        $em = self::$application->getContainer()->get(self::$config['app']['orm'])->getEntityManager();

        $this->schemaTool = new SchemaTool($em);
        
        $entities = array_diff(scandir(self::$config['app']['path'] . '/Entities'), ['.', '..']);
        $entitiesNamespace = '\App\Entities\\';

        $metaClasses = array();

        foreach ($entities as $entity) {
            $metaClasses[] = $em->getClassMetadata($entitiesNamespace . explode('.', $entity)[0]);
        }

        $this->metaClasses = $metaClasses;

        $this->schemaTool->createSchema($metaClasses);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->schemaTool->dropSchema($this->metaClasses);
    }
}