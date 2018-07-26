<?php

use App\Containers\DatabaseContainer;
use App\Containers\RepositoriesContainer;
use App\Containers\DumpsContainer;
use App\Containers\ServicesContainer;
use App\Containers\ControllersContainer;
use App\Containers\DumpsFactoriesContainer;
use App\Containers\EntityContainer;
use App\Containers\QuerysContainer;
use App\Containers\CommandsContainer;
use App\Containers\FactoriesContainer;

return [
    'app' => [
        'path'  => realpath(__DIR__ . '/../src/App/'),
        'debug' => getenv('APP_DEBUG_MODE'),
        'containers' => [
            DatabaseContainer::class,
            RepositoriesContainer::class,
            DumpsContainer::class,
            ServicesContainer::class,
            ControllersContainer::class,
            DumpsFactoriesContainer::class,
            EntityContainer::class,
            QuerysContainer::class,
            CommandsContainer::class,
            FactoriesContainer::class
        ],
        'token_secret' => getenv('APP_JWT_SECRET'),
        'routes' => realpath(__DIR__ . '/../src/App/routes.php'),
        'orm' => 'doctrine',
        'max_page_count' => 15,
        'allowed_origins' => getenv('ALLOWED_ORIGINS')
    ],
    'database' => [
        'driver'   => getenv('DB_DRIVER'),
        'user'     => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'host'     => getenv('DB_HOST'),
        'port'     => getenv('DB_PORT'),
        'dbname'   => getenv('DB_NAME')
    ]
];