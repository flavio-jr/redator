<?php

namespace App\Repositories\UserRepository\Security;

use App\Services\UserSession\UserSessionInterface as UserSession;
use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use App\Exceptions\WrongCredentialsException;

final class UserSecurity implements UserSecurityInterface
{
    /**
     * The user session service
     * @var UserSession
     */
    private $userSession;

    /**
     * The user query repository
     * @var UserQuery
     */
    private $userQuery;

    public function __construct(
        UserSession $userSession,
        UserQuery $userQuery
    ) {
        $this->userSession = $userSession;
        $this->userQuery = $userQuery;   
    }

    /**
     * @inheritdoc
     */
    public function getAccessToken(string $username, string $password): string
    {
        $user = $this->userQuery
            ->findByUsername($username);

        if (password_verify($password, $user->getPassword())) {
            return $this->userSession
                ->createNewToken($user->getId());
        }

        throw new WrongCredentialsException;
    }
}