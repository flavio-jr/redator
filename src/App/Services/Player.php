<?php

namespace App\Services;

use Lcobucci\JWT\Parser;
use App\Repositories\UserRepository;
use App\Entities\User;

final class Player
{
    /**
     * The logged user
     * @var User
     */
    private static $user;

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
     * Sets the user based on an JWT Token
     * @method setPlayerFromToken
     * @param string $jwt
     */
    public function setPlayerFromToken(string $jwt)
    {
        $token = (new Parser())->parse($jwt);

        self::$user = $this->userRepository->find($token->getHeader('jti'));
    }

    /**
     * Simply sets an user as the Player
     * @method setPlayer
     * @param User $user
     */
    public static function setPlayer(User $user)
    {
        self::$user = $user;
    }

    /**
     * Terminate the user session
     * @method gameOver
     */
    public static function gameOver()
    {
        self::$user = null;
    }

    /**
     * Return the current logged user
     * @method user
     * @return User
     */
    public static function user()
    {
        return self::$user;
    }
}