<?php

namespace App\RequestValidators;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

class UserRegistration
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('username', V::stringType()->length(2, 60))
                ->key('name', V::notOptional()->length(2, 100))
                ->key('password', V::notOptional());

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}