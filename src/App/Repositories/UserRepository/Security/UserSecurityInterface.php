<?php

namespace App\Repositories\UserRepository\Security;

use App\Exceptions\WrongCredentialsException;

interface UserSecurityInterface
{
    /**
     * Get access token for the right credentials
     * @method getAccessToken
     * @param string $username
     * @param string $password
     * @return string The token
     * @throws WrongCredentialsException
     */
    public function getAccessToken(string $username, string $password): string;
}