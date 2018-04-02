<?php

namespace App\RequestValidators;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

final class PublicationsInfo
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('page', V::intVal(), false)
                ->key('category', V::stringType(), false)
                ->key('title', V::stringType(), false)
                ->key('min_date', V::date(), false)
                ->key('max_date', V::date(), false);

            $rules->assert($request->getQueryParams());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}