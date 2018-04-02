<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\UserSession;
use Slim\Http\Request;
use Slim\Http\Response;

final class LoginController
{
    /**
     * The user repository
     * @var UserRepository
     */
    private $userRepository;

    /**
     * The user session service
     * @var UserSession
     */
    private $userSessionService;

    public function __construct(
        UserRepository $userRepository,
        UserSession $userSessionService
    ) {
        $this->userRepository = $userRepository;
        $this->userSessionService = $userSessionService;        
    }

    /**
     * Authorizes user return an token access
     * @method login
     * @param Request $request
     * @param Response $response
     */
    public function login(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();

        $user = $this->userRepository
            ->getUserByCredentials($requestBody['username'], $requestBody['password']);
        
        if (!$user) {
            return $response->write(json_encode(['message' => 'Invalid user']))->withStatus(403);    
        }

        $token = $this->userSessionService->createNewToken($user->getId());

        return $response->write(json_encode(['token' => $token]))->withStatus(200);
    }
}