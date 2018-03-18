<?php

namespace App\Containers;

use Slim\Container;
use App\Database\EntityManager;

class DatabaseContainer
{
    public function register(Container $container, array $config)
    {
        $container['doctrine'] = function ($c) use ($config) {
            return new EntityManager($config);
        };
    }
}