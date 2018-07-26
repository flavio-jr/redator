<?php

namespace App;

use Slim\App;
use Slim\Container;
use App\Handlers\ErrorHandler;
use App\Handlers\NotFoundHandler;
use App\Handlers\NotAllowedHandler;
use App\Handlers\PhpErrorHandler;

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

        $app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });
        
        $app->add(function ($req, $res, $next) {
            $response = $next($req, $res);

            return $response
                ->withHeader('Access-Control-Allow-Origin', config()['app']['allowed_origins'])
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });
        

        $container = $app->getContainer();

        $this->buildHandlers($container);

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

    private function buildHandlers(Container $container)
    {
        $container['errorHandler'] = function () {
            return new ErrorHandler();
        };

        $container['notFoundHandler'] = function () {
            return new NotFoundHandler();
        };

        $container['notAllowedHandler'] = function () {
            return new NotAllowedHandler();
        };

        $container['phpErrorHandler'] = function () {
            return new PhpErrorHandler();
        };
    }
}