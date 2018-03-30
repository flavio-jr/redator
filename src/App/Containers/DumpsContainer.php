<?php

namespace App\Containers;

use Slim\Container;
use Faker\Factory;
use App\Services\Persister;
use App\Dumps\UserDump;
use App\Dumps\ApplicationDump;
use App\Dumps\CategoryDump;

class DumpsContainer
{
    const DUMPS_NAMESPACE = 'App\Dumps\\';

    public function register(Container $container, array $config)
    {
        $container[self::DUMPS_NAMESPACE . 'UserDump'] = function ($c) {
            return new UserDump(Factory::create(), $c->get('PersisterService'));
        };

        $container[self::DUMPS_NAMESPACE . 'ApplicationDump'] = function ($c) {
            return new ApplicationDump(
                Factory::create(), 
                $c->get('PersisterService'), 
                $c->get(self::DUMPS_NAMESPACE . 'UserDump'
            ));
        };

        $container[self::DUMPS_NAMESPACE . 'CategoryDump'] = function ($c) {
            return new CategoryDump(Factory::create(), $c->get('PersisterService'));
        };
    }
}