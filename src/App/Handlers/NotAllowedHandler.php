<?php

namespace App\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class NotAllowedHandler
{
    public function __invoke(Request $request, Response $response, array $methods)
    {
        $methodsList = implode(', ', $methods);

        $response
            ->getBody()
            ->write('The method must be one of: ' . $methodsList);

        return $response
            ->withHeader('Allow', $methodsList)
            ->withStatus(405);
    }
}