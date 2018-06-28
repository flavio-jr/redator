<?php

use Symfony\Component\Yaml\Yaml;
use App\Application;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;

require realpath(__DIR__ . '/../vendor/autoload.php');

class DBBuilder
{
    public function __destruct()
    {
        if (file_exists(__DIR__ . '/test.sqlite')) {
            unlink(__DIR__ . '/test.sqlite');
        }
    }

    public function build()
    {
        putenv('APP_ENV=TEST');

        ini_set('memory_limit', '150M');

        $config = require realpath(__DIR__ . '/../config/config.php');

        $config['db_path'] = __DIR__ . '/test.sqlite';
        $config['test_driver'] = getenv('DB_TEST_DRIVER');

        $application = (new Application($config))->make();

        $em = $application->getContainer()->get('orm')->getEntityManager();

        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($this->getMetaClass($em, $config));
    }

    private function getMetaClass(EntityManager $em, array $config)
    {
        $entities = array_diff(scandir($config['app']['path'] . '/Entities'), ['.', '..']);
        $entitiesNamespace = '\App\Entities\\';
    
        $metaClasses = array();
    
        foreach ($entities as $entity) {
            $metaClasses[] = $em->getClassMetadata($entitiesNamespace . explode('.', $entity)[0]);
        }
    
        return $metaClasses;
    }
}

if (file_exists(realpath(__DIR__ . '/../.env'))) {
    (new Dotenv(realpath(__DIR__ . '/../')))->load();
}

$dbBuilder = new DBBuilder();
$dbBuilder->build();