<?php

namespace App\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class PhpErrorHandler
{
    public function __invoke(Request $request, Response $response, \Throwable $error)
    {
        $response
            ->getBody()
            ->write('Something went wrong: ' . $error->getMessage());

        return $response
            ->withStatus(500);
    }
}