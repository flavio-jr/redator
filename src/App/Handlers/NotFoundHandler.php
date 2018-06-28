<?php

namespace App\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class NotFoundHandler
{
    public function __invoke(Request $request, Response $response)
    {
        $response
            ->getBody()
            ->write('The resource could not be found');

        return $response
            ->withStatus(404);
    }
}