<?php

namespace App\RequestValidators;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

final class UserStateManagerRequest
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('status', V::intVal()->notOptional());

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}