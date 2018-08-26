<?php

namespace App\RequestValidators;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;
use App\Database\Types\PublicationStatus;

final class PublicationModifier
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key(
                'status',
                V::stringType()->in(array_keys(PublicationStatus::getEnum()))
            );

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}