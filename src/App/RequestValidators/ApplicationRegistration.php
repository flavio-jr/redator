<?php

namespace App\RequestValidators;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Database\Types\ApplicationType;

final class ApplicationRegistration
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $rules = V::key('name', V::notOptional()->stringType()->length(2, 45))
                ->key('description', V::notOptional()->stringType())
                ->key('url', V::notOptional()->domain())
                ->key('type', V::notOptional()->in(array_keys(ApplicationType::getApplicationTypes())));

            $rules->assert($request->getParsedBody());

            return $next($request, $response);
        } catch (NestedValidationException $e) {
            return $response->write(json_encode($e->getMessages()))->withStatus(412);
        }
    }
}