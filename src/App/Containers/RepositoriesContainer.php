<?php

namespace App\Containers;

use Slim\Container;
use App\Repositories\UserRepository;

class RepositoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserRepository'] = function ($c) use ($config) {
            return new UserRepository($c->get('doctrine')->getEntityManager());
        };
    }
}