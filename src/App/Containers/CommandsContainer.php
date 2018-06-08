<?php

namespace App\Containers;

use Slim\Container;
use App\Commands\CreateMasterUser;
use App\Repositories\UserMasterRepository\Store\UserMasterStore;
use App\Commands\UpdateMasterUser;
use App\Repositories\UserMasterRepository\Update\UserMasterUpdate;

class CommandsContainer
{
    public function register(Container $container, array $config)
    {
        $container[CreateMasterUser::class] = function (Container $c) {
            return new CreateMasterUser($c->get(UserMasterStore::class));
        };

        $container[UpdateMasterUser::class] = function (Container $c) {
            return new UpdateMasterUser($c->get(UserMasterUpdate::class));
        };
    }
}