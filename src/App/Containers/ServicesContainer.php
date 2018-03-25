<?php

namespace App\Containers;

use Slim\Container;
use App\Services\UserSession;

class ServicesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserSession'] = function ($c) {
            return new UserSession();
        };
    }
}