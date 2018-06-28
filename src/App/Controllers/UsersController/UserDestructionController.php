<?php

namespace App\Controllers\UsersController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\UserRepository\Destruction\UserDestructionInterface as UserDestruction;
use App\Exceptions\UserNotAllowedToRemoveUsers;
use App\Exceptions\EntityNotFoundException;

final class UserDestructionController
{
    /**
     * The user destruction repository
     * @var UserDestruction
     */
    private $userDestruction;

    public function __construct(UserDestruction $userDestruction)
    {
        $this->userDestruction = $userDestruction;
    }

    public function destroy(Request $request, Response $response, array $args)
    {
        try {
            $this->userDestruction
                ->destroy($args['username']);

            $response
                ->getBody()
                ->write('User successfully deleted');

            return $response->withStatus(200);
        } catch (UserNotAllowedToRemoveUsers $e) {
            $response
                ->getBody()
                ->write('The user can\'t delete other users');

            return $response->withStatus(403);
        } catch (EntityNotFoundException $e) {
            $response
                ->getBody()
                ->write("The {$e->getEntityName()} could not be found");

            return $response->withStatus(404);
        }
    }
}