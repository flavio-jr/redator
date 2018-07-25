<?php

namespace App\Controllers\UsersController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\UserRepository\Collection\UserCollectionInterface as UserCollection;
use App\Exceptions\UserNotAllowedException;

final class GetUsersController
{
    /**
     * The user collection repository
     * @var UserCollection
     */
    private $userCollection;

    public function __construct(UserCollection $userCollection)
    {
        $this->userCollection = $userCollection;
    }

    public function get(Request $request, Response $response, array $args)
    {
        try {
            $users = $this->userCollection
                ->getAll();

            $response
                ->getBody()
                ->write(json_encode(['users' => $users]));

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (UserNotAllowedException $e) {
            $response
                ->getBody()
                ->write('Only master user can view other users data');

            return $response->withStatus(403);
        }
    }
}