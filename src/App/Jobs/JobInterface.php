<?php

namespace App\Jobs;

use Slim\Container;

interface JobInterface
{
    public function __construct(Container $container);

    public function handle(array $params);
}