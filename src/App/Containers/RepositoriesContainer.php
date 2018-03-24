<?php

namespace App\Containers;

use Slim\Container;
use App\Repositories\UserRepository;
use App\Services\Persister;

class RepositoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserRepository'] = function ($c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            return new UserRepository($em, new Persister($em));
        };
    }
}