<?php

namespace App\Containers;

use Slim\Container;
use Faker\Factory;
use App\Services\Persister;

class DumpsContainer
{
    const DUMPS_NAMESPACE = 'App\Dumps\\';

    public function register(Container $container, array $config)
    {
        $dumpsPath = $config['app']['path'] . 'Dumps/';

        $files = array_diff(scandir($dumpsPath), ['.', '..']);

        foreach ($files as $file)
        {
            $class = self::DUMPS_NAMESPACE . explode('.', $file)[0];

            $container[$class] = function ($c) use ($class) {
                return new $class(Factory::create(), new Persister($c['doctrine']->getEntityManager()));
            };
        }
    }
}