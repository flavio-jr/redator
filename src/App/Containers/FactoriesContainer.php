<?php

namespace App\Containers;

use Psr\Container\ContainerInterface as Container;
use App\Factorys\Application\Query\ApplicationQueryFactory;

class FactoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container[ApplicationQueryFactory::class] = function (Container $c) {
            return new ApplicationQueryFactory($c);
        };
    }
}