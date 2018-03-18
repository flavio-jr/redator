<?php

namespace App\Database;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;

class EntityManager implements ModelManagerInterface
{
    private $entityManager;

    public function __construct(array $config)
    {
        $this->build($config);
    }

    public function getModel(string $model)
    {
        return $this->entityManager->getRepository($model);   
    }

    public function build(array $config)
    {
        \Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');

        $setup = Setup::createAnnotationMetadataConfiguration(
            [$config['app']['path'] . 'Entities'],
            $config['app']['debug'],
            null,
            null,
            false
        );

        $this->entityManager = DoctrineEntityManager::create([
            'driver'   => $config['database']['driver'],
            'user'     => $config['database']['user'],
            'password' => $config['database']['password'],
            'host'     => $config['database']['host'],
            'port'     => $config['database']['port'],
            'dbname'   => $config['database']['dbname'] 
        ], $setup);
    }

    public function getEntityManager(): DoctrineEntityManager
    {
        return $this->entityManager;
    }
}