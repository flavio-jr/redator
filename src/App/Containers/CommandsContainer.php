<?php

namespace App\Containers;

use Slim\Container;
use App\Commands\CreateMasterUser;

class CommandsContainer
{
    public function register(Container $container, array $config)
    {
        $container[CreateMasterUser::class] = function (Container $c) use ($config) {
            return new CreateMasterUser();
        };
    }
}