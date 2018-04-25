<?php

require_once('vendor/autoload.php');

use Symfony\Component\Yaml\Yaml;
use App\Application;
use Dotenv\Dotenv;

$appConfig = Yaml::parseFile(__DIR__ . '/config/app.yml');
$appConfig['app']['path'] = __DIR__ . '/' . $appConfig['app']['path'];

$appConfig['app']['routes'] = __DIR__ . "/{$appConfig['app']['routes']}";

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}

$appConfig['database'] = [
    'driver'   => getenv('DB_DRIVER'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'dbname'   => getenv('DB_NAME')
];

$app = (new Application($appConfig))->make();