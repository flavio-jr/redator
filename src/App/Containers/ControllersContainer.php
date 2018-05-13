<?php

namespace App\Containers;

use Slim\Container;
use App\Controllers\LoginController;
use App\Controllers\UsersController;
use App\Controllers\ApplicationsController;
use App\Controllers\PublicationsController;
use App\Controllers\CategoriesController;
use App\Controllers\UsersController\UserStoreController;
use App\Repositories\UserRepository\Store\UserStore;
use App\Controllers\UsersController\UserUpdateController;
use App\Repositories\UserRepository\Update\UserUpdate;

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

        $container['App\Controllers\ApplicationsController'] = function ($c) {
            return new ApplicationsController($c->get('ApplicationRepository'));
        };

        $container['App\Controllers\PublicationsController'] = function ($c) {
            return new PublicationsController($c->get('PublicationRepository'), $c->get('App\Filters\PublicationFilter'));
        };

        $container['App\Controllers\CategoriesController'] = function ($c) {
            return new CategoriesController($c->get('CategoryRepository'));
        };

        $container[UserStoreController::class] = function (Container $c) {
            return new UserStoreController($c->get(UserStore::class));
        };

        $container[UserUpdateController::class] = function (Container $c) {
            $userUpdateRepository = $c->get(UserUpdate::class);

            return new UserUpdateController($userUpdateRepository);
        };
    }
}