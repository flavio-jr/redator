<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit;
use Slim\Http\Environment;
use Slim\Http\Request;
use App\Application;
use Symfony\Component\Yaml\Yaml;
use Dotenv\Dotenv;

class TestCase extends PHPUnit
{
    protected $application;
    protected $config;
    protected $container;

    public function createApplication()
    {
        putenv('APP_ENV=TEST');

        $this->config = Yaml::parseFile(realpath(__DIR__ . '/../config/app.yml'));
        $this->config['db_path'] = __DIR__ . '/test.sqlite';
        $this->config['test_driver'] = getenv('DB_TEST_DRIVER');
        $this->config['app']['templates_path'] = realpath(__DIR__ . '/../public/templates');
        $this->config['app']['worker_dir'] = realpath(__DIR__ . "/../{$this->config['app']['worker_dir']}");
        
        $this->application = (new Application($this->config))->make();
        $this->container = $this->application->getContainer();

        $this->swipeMailEnv();
    }

    public function setUp()
    {
        parent::setUp();

        $this->createApplication();

        if (method_exists($this, 'setUpDatabase')) {
            $this->setUpDatabase();
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        if (method_exists($this, 'dropDatabase')) {
            $this->dropDatabase();
        }

        $refl = new \ReflectionObject($this);

        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }

    private function makeRequest($url, $method, array $data = [], $queryString = '')
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI'    => $url,
            'QUERY_STRING'   => $queryString,
            'CONTENT_TYPE'   => 'application/json'
        ]);

        $request = Request::createFromEnvironment($env)->withParsedBody($data);

        $this->application->getContainer()['request'] = $request;

        return $this->application->run(true);
    }

    protected function get($route, $queryString = '')
    {
        return $this->makeRequest($route, 'GET', [], $queryString);
    }

    protected function post($route, array $data)
    {
        return $this->makeRequest($route, 'POST', $data);
    }

    protected function put($route, array $data)
    {
        return $this->makeRequest($route, 'PUT', $data);
    }

    protected function delete($route)
    {
        return $this->makeRequest($route, 'DELETE');
    }

    protected function assertDatabaseHave($entity)
    {
        $register = $this->application
            ->getContainer()
            ->get('doctrine')
            ->getEntityManager()
            ->find(get_class($entity), $entity->getId());

        $this->assertNotNull($register);
    }

    protected function assertDatabaseDoenstHave(string $id, $entity)
    {
        $register = $this->application
            ->getContainer()
            ->get('doctrine')
            ->getEntityManager()
            ->find(get_class($entity), $id);

        $this->assertNull($register);
    }

    private function swipeMailEnv()
    {
        putenv('MAIL_HOST=' . getenv('MAIL_TEST_HOST'));
        putenv('MAIL_USER=' . getenv('MAIL_TEST_USER'));
        putenv('MAIL_PASSWORD=' . getenv('MAIL_TEST_PASSWORD'));
        putenv('MAIL_PORT=' . getenv('MAIL_TEST_PORT'));
    }
}