<?php

namespace App\Controllers\UsersController;

use App\Repositories\UserRepository\Update\UserUpdateInterface as UserUpdate;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class UserUpdateController
{
    /**
     * @var UserUpdate
     */
    private $userUpdateRepository;

    public function __construct(UserUpdate $userUpdateRepository)
    {
        $this->userUpdateRepository = $userUpdateRepository;
    }

    public function update(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $userUpdated = $this->userUpdateRepository->update($data);

            if ($userUpdated) {
                $response
                    ->getBody()
                    ->write('User successfully updated');

                return $response->withStatus(200);
            }

            $response
                ->getBody()
                ->write('The user could not be updated');

            return $response->withStatus(403);
        } catch (UniqueConstraintViolationException $e) {
            $response
                ->getBody()
                ->write('Username already taken');

            return $response->withStatus(412);
        }
    }
}