<?php

namespace App\Containers;

use Slim\Container;
use App\Services\UserSession;
use App\Services\Player;
use App\Services\Persister;

class ServicesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserSession'] = function ($c) {
            return new UserSession();
        };

        $container['Player'] = function ($c) {
            return new Player($c->get('UserRepository'));
        };

        $container['PersisterService'] = function ($c) {
            return new Persister($c->get('doctrine')->getEntityManager());
        };
    }
}