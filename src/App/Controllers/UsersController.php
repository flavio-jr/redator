<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Exceptions\UniqueFieldException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class UsersController
{
    /**
     * The user repository
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     * @method store
     * @param Request $request
     * @param Response $response
     */
    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->userRepository->create($data);

            return $response->write('User sucessfully created')->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    /**
     * Updates an user
     * @method update
     * @param Request $request
     * @param Response $response
     */
    public function update(Request $request, Response $response, array $args)
    {
        try {
            $data = $request->getParsedBody();

            $this->userRepository->update($args['user_id'], $data);

            return $response->write('User data sucessfully updated')->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    /**
     * Checks for username availability
     * @method usernameAvailaibility
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function usernameAvailaibility(Request $request, Response $response, array $args)
    {
        $userNameAvailaibility = $this->userRepository->isUsernameAvailable($args['username']);

        return $response
            ->write(json_encode([
                'available' => (int) $userNameAvailaibility
            ]))
            ->withStatus(200);
    }
}