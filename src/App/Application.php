<?php

namespace App;

use Slim\App;
use Slim\Container;

class Application
{
    /**
     * Application settings
     *
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function make(): App
    {
        $slimConfig = [
            'settings' => [
                'displayErrorDetails' => $this->config['app']['debug']
            ]
        ];

        $app = new App($slimConfig);

        $routes = require_once($this->config['app']['routes']);

        $container = $app->getContainer();

        $this->buildContainer($container);

        return $app;
    }

    private function buildContainer(Container $container)
    {
        $appBuilds = $this->config['app']['containers'];

        foreach ($appBuilds as $builder) {
            $appContainer = new $builder;

            $appContainer->register($container, $this->config);
        }
    }
}