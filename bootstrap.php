<?php

use App\Application;
use Dotenv\Dotenv;

require_once('vendor/autoload.php');

$appConfig = require(__DIR__ . '/config/config.php'); 

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}

$app = (new Application($appConfig))->make();