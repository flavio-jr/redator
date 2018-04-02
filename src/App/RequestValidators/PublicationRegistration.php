<?php

namespace App\RequestValidators;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;

final class PublicationRegistration
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('title', V::stringType()->notOptional()->length(1, 80))
                ->key('description', V::stringType()->notOptional()->length(2, 120))
                ->key('application', V::stringType()->notOptional())
                ->key('category', V::stringType()->notOptional())
                ->key('body', V::stringType()->notOptional());

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}