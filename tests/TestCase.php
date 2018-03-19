<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit;
use Slim\Http\Environment;
use Slim\Http\Request;
use App\Application;
use Symfony\Component\Yaml\Yaml;

class TestCase extends PHPUnit
{
    protected static $application;

    public static function setUpBeforeClass()
    {
        $config = Yaml::parseFile(realpath(__DIR__ . '/../config/app.yml'));
        self::$application = (new Application($config))->make();
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

        self::$application->getContainer()['request'] = $request;

        return self::$application->run(true);
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
}