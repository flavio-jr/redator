<?php

namespace App\Services\QueueWorkers;

interface WorkerInterface
{
    public function fire(string $job, array $params);
}