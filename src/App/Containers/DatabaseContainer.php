<?php

namespace App\Containers;

use Slim\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DatabaseContainer
{
    public function register(Container $container, array $config)
    {
        $container['em'] = function ($c) use ($config) {
            $config = Setup::createAnnotationMetadataConfiguration(
                $config['app']['path'] . 'Entities',
                $config['app']['debug']
            );

            return EntityManager::create([
                'driver'   => $config['database']['driver'],
                'user'     => $config['database']['user'],
                'password' => $config['database']['password'],
                'host'     => $config['database']['host'],
                'port'     => $config['database']['port'],
                'dbname'   => $config['database']['database'] 
            ], $config);
        };
    }
}