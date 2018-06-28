<?php

namespace App\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ErrorHandler
{
    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        $response
            ->getBody()
            ->write(json_encode(['exception' => $exception->getMessage()]));

        return $response
            ->withStatus(500);
    }
}