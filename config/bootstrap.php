<?php

require_once('../vendor/autoload.php');

use Symfony\Component\Yaml\Yaml;
use App\Application;
use Dotenv\Dotenv;

(new Dotenv('../'))->load();

$appConfig = Yaml::parseFile(__DIR__ . '/app.yml');
$appConfig['database'] = [
    'driver'   => getenv('DB_DRIVER'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'dbname'   => getenv('DB_NAME')
];

$app = (new Application($appConfig))->make();