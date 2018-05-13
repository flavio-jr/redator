<?php

namespace App\Controllers\UsersController;

use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use Slim\Http\Request;
use Slim\Http\Response;

final class UserQueryController
{
    /**
     * The user repository for querying information
     * @var UserQuery
     */
    private $userQueryRepository;

    public function __construct(UserQuery $userQueryRepository)
    {
        $this->userQueryRepository = $userQueryRepository;
    }

    public function getByUsername(Request $request, Response $response, array $args)
    {   
        $user = $this->userQueryRepository->findByUsername($args['username']);

        if ($user) {
            return $response->write(json_encode(['user' => $user]))->withStatus(200);
        }

        return $response->write('User not found')->withStatus(404);
    }
}