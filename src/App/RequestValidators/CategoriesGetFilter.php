<?php

namespace App\RequestValidators;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

final class CategoriesGetFilter
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('page', V::intVal(), false)
                ->key('name', V::stringType(), false);

            $rules->assert($request->getQueryParams());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}