<?php

namespace App\RequestValidators;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

class Login
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('username', V::notOptional())
                ->key('password', V::notOptional());

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}