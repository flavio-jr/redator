<?php

namespace App\RequestValidators;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;

final class CategoryRegistration
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('name', V::stringType()->notOptional()->length(2, 60));

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}