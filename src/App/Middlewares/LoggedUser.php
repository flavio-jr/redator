<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\UserSession;
use App\Services\Player;

final class LoggedUser
{
    /**
     * The user session service
     * @var UserSession
     */
    private $userSessionService;

    /**
     * The player service
     * @var Player
     */
    private $player;

    public function __construct(
        UserSession $userSession,
        Player $player
    ) {
        $this->userSessionService = $userSession;
        $this->player = $player;
    }

    /**
     * Check user identity to authorize or not
     * @param Request $request
     * @param Response $response
     * @param callback $next
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if (getenv('APP_ENV') === 'TEST') {
            return $next($request, $response);
        }

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