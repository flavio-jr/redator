<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\UserSession;
use App\Services\Player;

final class LoggedUser
{
    private $userSessionService;
    private $player;

    public function __construct(
        UserSession $userSession,
        Player $player
    ) {
        $this->userSessionService = $userSession;
        $this->player = $player;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $jwt = $request->getHeader('Authorization');

        if (!count($jwt)) {
            return $response->write(json_encode(['error' => 'Token access required']))->withStatus(403);
        }

        if ($this->userSessionService->isValidToken($jwt[0])) {
            $this->player->setPlayerFromToken($jwt[0]);

            return $next($request, $response);
        }

        return $response->write(json_encode(['error' => 'Invalid token']))->withStatus(403);
    }
}