<?php

namespace App\Services\UserSession;

interface UserSessionInterface
{
    /**
     * Token expiration time
     * @var int
     */
    const EXPIRATION_TIME = 3600;

    /**
     * Creates an new token
     * @method createNewToken
     * @param string $id
     * @return string
     */
    public function createNewToken(string $id): string;

    /**
     * Check is an given token is valid
     * @method isValidToken
     * @param string $token
     * @return bool
     */
    public function isValidToken(string $token): bool;
}