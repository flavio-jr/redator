<?php

namespace App\Controllers\UsersController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\UserRepository\State\UserStateManagerInterface as UserStateManager;
use App\Exceptions\UserNotAllowedException;

final class UserStateController
{
    /**
     * The user state manager repository
     * @var UserStateManager
     */
    private $userStateManager;

    public function __construct(UserStateManager $userStateManager)
    {
        $this->userStateManager = $userStateManager;
    }

    public function changeStatus(Request $request, Response $response, array $args)
    {
         try {
             $newStatus = $request->getParsedBody()['status'];

             $this->userStateManager
                ->changeStatus($args['username'], (bool) $newStatus);

            $response
                ->getBody()
                ->write('The user state was successfully updated');

            return $response->withStatus(200);
         } catch (UserNotAllowedException $e) {
             $response
                ->getBody()
                ->write('The user can\'t change users status');

            return $response->withStatus(403);
         }
    }
}
