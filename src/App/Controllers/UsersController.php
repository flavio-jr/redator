<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;

final class UsersController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->userRepository->create($data);

            return $response->write('User sucessfully created')->withStatus(200);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }
}