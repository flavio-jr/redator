<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\UserSession;

final class LoggedUser
{
    private $userSessionService;

    public function __construct(UserSession $userSession)
    {
        $this->userSessionService = $userSession;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $jwt = $request->getHeader('Authorization');

        if (!count($jwt)) {
            return $response->write(json_encode(['error' => 'Token access required']))->withStatus(403);
        }

        if ($this->userSessionService->isValidToken($jwt[0])) {
            return $next($request, $response);
        }

        return $response->write(json_encode(['error' => 'Invalid token']))->withStatus(403);
    }
}