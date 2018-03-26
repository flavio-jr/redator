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

    public const PREFIX = '/app';

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

        $container = $app->getContainer();

        $this->buildContainer($container);

        $this->buildDatabase($container, $this->config['app']['orm']);

        require $this->config['app']['routes'];

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

    private function buildDatabase(Container $container, string $orm)
    {
        $container['orm'] = $container->get($orm);
    }
}