<?php

namespace App\Services\QueueWorkers;

use hollodotme\FastCGI\SocketConnections\UnixDomainSocket;
use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\PostRequest;

class FPMWorker implements WorkerInterface
{ 
    /**
     * The FPM client
     * @var Client
     */
    private $client;

    /**
     * The directory in which the jobs will be executed
     * @var string
     */
    private $workerDir;

    public function __construct(string $workerDir)
    {
        $this->workerDir = $workerDir;
        $connection = new UnixDomainSocket(getenv('FPM_SOCKET'), 5000, 5000);
        $this->client = new Client($connection);
    }

    public function fire(string $job, array $params)
    {
        $content = http_build_query(['job' => $job, 'payload' => json_encode($params)]);
        $request = new PostRequest($this->workerDir, $content);

        $this->client->sendAsyncRequest($request);
    }
}