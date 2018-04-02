<?php

namespace App\Containers;

use Slim\Container;
use App\Dumps\DumpsFactories\DumpFactory;

class DumpsFactoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container['DumpFactory'] = function ($c) {
            return new DumpFactory();
        };
    }
}