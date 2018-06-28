<?php

namespace App\Controllers\UsersController;

use App\Repositories\UserRepository\Update\UserUpdateInterface as UserUpdate;
use Slim\Http\Request;
use Slim\Http\Response;
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
                return $response->write('User successfully updated')->withStatus(200);    
            }

            return $response->write('The user could not be updated')->withStatus(403);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        }
    }
}