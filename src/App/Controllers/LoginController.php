<?php

namespace App\Controllers;

use App\Repositories\UserRepository\Security\UserSecurityInterface as UserSecurity;
use App\Services\UserSession\UserSessionInterface as UserSession;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\WrongCredentialsException;
use App\Exceptions\EntityNotFoundException;

final class LoginController
{
    /**
     * The user repository
     * @var UserSecurity
     */
    private $userSecurity;

    /**
     * The user session service
     * @var UserSession
     */
    private $userSessionService;

    public function __construct(
        UserSecurity $userSecurity,
        UserSession $userSessionService
    ) {
        $this->userSecurity = $userSecurity;
        $this->userSessionService = $userSessionService;        
    }

    /**
     * Authorizes user return an token access
     * @method login
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function login(Request $request, Response $response)
    {
        try {
            $requestBody = $request->getParsedBody();

            $token = $this->userSecurity
                ->getAccessToken($requestBody['username'], $requestBody['password']);

            $response->getBody()
                ->write(json_encode(['token' => $token]));

            return $response->withStatus(200);
        } catch (WrongCredentialsException | EntityNotFoundException $e) {
            $response->getBody()
                ->write('ERROR: INVALID CREDENTIALS');

            return $response->withStatus(403);
        }
    }
}