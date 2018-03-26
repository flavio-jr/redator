<?php

namespace App\Containers;

use App\Controllers\LoginController;
use Slim\Container;
use App\Controllers\UsersController;

class ControllersContainer
{
    public function register(Container $container, array $config)
    {
        $container['App\Controllers\LoginController'] = function ($c) {
            return new LoginController($c->get('UserRepository'), $c->get('UserSession'));
        };

        $container['App\Controllers\UsersController'] = function ($c) {
            return new UsersController($c->get('UserRepository'));
        };
    }
}