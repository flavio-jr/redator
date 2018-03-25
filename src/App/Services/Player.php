<?php

namespace App\Services;

use Lcobucci\JWT\Parser;
use App\Repositories\UserRepository;

final class Player
{
    private static $user;

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function setPlayerFromToken(string $jwt)
    {
        $token = (new Parser())->parse($jwt);

        self::$user = $this->userRepository->find($token->getHeader('jti'));
    }

    public static function user()
    {
        return self::$user;
    }
}