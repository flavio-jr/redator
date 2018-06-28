<?php

namespace App\Containers;

use Slim\Container;
use App\Querys\Publications\PublicationQuery;
use Slim\Http\Request;
use App\Querys\Categories\CategoriesQuery;

class QuerysContainer
{
    public function register(Container $container, array $config)
    {
        $container[PublicationQuery::class] = function (Container $c) use ($config) {
            $em = $c->get('orm')->getEntitymanager();

            return new PublicationQuery($config['app']['max_per_page'] ?? 15, $em);
        };

        $container[CategoriesQuery::class] = function (Container $c) {
            $em = $c->get('orm')->getEntityManager();

            return new CategoriesQuery($em);
        };
    }
}