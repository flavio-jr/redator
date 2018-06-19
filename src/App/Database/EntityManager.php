<?php

namespace App\Database;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\EventManager;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\DoctrineExtensions;

class EntityManager implements ModelManagerInterface
{
    /**
     * The doctrine entity manager
     * @var DoctrineEntityManager
     */
    private $entityManager;

    public function __construct(array $config)
    {
        if (getenv('APP_ENV') === 'TEST') {
            $this->buildForTestEnvironment($config);
            return;
        }
        
        $this->build($config);
    }

    /**
     * Retrieves the entity manager
     * @method getEntityManager
     * @return DoctrineEntityManager
     */
    public function getEntityManager(): DoctrineEntityManager
    {
        return $this->entityManager;
    }

    /**
     * Retrieves an repository for a given model
     * @method getModel
     * @param string $model
     * @return EntityRepository
     */
    public function getModel(string $model)
    {
        return $this->entityManager->getRepository($model);   
    }

    /**
     * Build the database config for production
     * @method build
     * @param array $config
     */
    public function build(array $config)
    {
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }

        $this->registerCustomAnnotations();

        $evm = $this->buildEventManager();

        $setup = Setup::createAnnotationMetadataConfiguration(
            [$config['app']['path'] . '/Entities'],
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
        ], $setup, $evm);
    }

    /**
     * Builds the database configuration for test enviroment
     * @method buildForTestEnvironment
     * @param array $config
     */
    private function buildForTestEnvironment(array $config)
    {
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }

        $this->registerCustomAnnotations();

        $evm = $this->buildEventManager();

        $setup = Setup::createAnnotationMetadataConfiguration(
            [$config['app']['path'] . '/Entities'],
            $config['app']['debug'],
            null,
            null,
            false
        );

        $this->entityManager = DoctrineEntityManager::create([
            'driver' => $config['test_driver'],
            'path'   => $config['db_path']
        ], $setup, $evm);
    }

    private function buildEventManager(): EventManager
    {
        $evm = new EventManager();
        
        // Sluggable
        $sluggable = new SluggableListener();
        $evm->addEventSubscriber($sluggable);

        return $evm;
    }

    private function registerCustomAnnotations()
    {
        // Doctrine extensions
        DoctrineExtensions::registerAnnotations();
    }
}