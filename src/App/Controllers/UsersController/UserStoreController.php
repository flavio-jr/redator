<?php

namespace App\Controllers\UsersController;

use App\Repositories\UserRepository\Store\UserStoreInterface as UserStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class UserStoreController
{
    /**
     * The user store repository
     * @var UserStore
     */
    private $userStore;

    public function __construct(UserStore $userStore)
    {
        $this->userStore = $userStore;
    }

    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->userStore->store($data);

            $response
                ->getBody()
                ->write('User sucessfully created');

            return $response->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        }
    }
}