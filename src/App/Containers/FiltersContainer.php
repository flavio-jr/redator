<?php

namespace App\Containers;

use Slim\Container;
use App\Filters\PublicationFilter;

class FiltersContainer
{
    public function register(Container $container, array $config)
    {
        $container['App\Filters\PublicationFilter'] = function (Container $c) {
            return new PublicationFilter($c->get('PublicationRepository'));
        };
    }
}