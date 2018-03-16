<?php

require_once('vendor/autoload.php');

use Symfony\Component\Yaml\Yaml;
use App\Application;
use Dotenv\Dotenv;

$appConfig = Yaml::parseFile(__DIR__ . '/config/app.yml');

$appConfig['app']['routes'] = __DIR__ . "/{$appConfig['app']['routes']}";
var_dump($_ENV['DEBUG']);
var_dump(getenv('DEBUG'));

exit(1);
if (is_null($_ENV['DEBUG']) || getenv('DEBUG')) {
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