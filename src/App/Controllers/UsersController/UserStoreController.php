<?php

namespace App\Controllers\UsersController;

use App\Repositories\UserRepository\Store\UserStoreInterface as UserStore;
use Slim\Http\Request;
use Slim\Http\Response;
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

            return $response->write('User sucessfully created')->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        }
    }
}