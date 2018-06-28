<?php

namespace App\Controllers\UsersController;

use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\EntityNotFoundException;

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
        try {
            $user = $this->userQueryRepository->findByUsername($args['username']);

            $response->getBody()
                ->write(json_encode(['user' => $user]));

            return $response->withStatus(200);
        } catch (EntityNotFoundException $e) {
            $response->getBody()
                ->write('User not found');

            return $response->withStatus(404);
        }
    }
}