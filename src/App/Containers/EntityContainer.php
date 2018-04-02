<?php

namespace App\Containers;

use Slim\Container;
use App\Entities\Application;
use App\Entities\User;
use App\Entities\Category;
use App\Entities\Publication;

class EntityContainer
{
    public function register(Container $container, array $config)
    {
        $container['User'] = function (Container $c) {
            return new User();
        };

        $container['Application'] = function (Container $c) {
            return new Application();
        };

        $container['Category'] = function (Container $c) {
            return new Category();
        };

        $container['Publication'] = function (Container $c) {
            return new Publication();
        };
    }
}