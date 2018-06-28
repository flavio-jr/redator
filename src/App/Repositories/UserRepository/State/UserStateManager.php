<?php

namespace App\Repositories\UserRepository\State;

use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use App\Services\Player;
use App\Exceptions\UserNotAllowedException;
use App\Services\Persister\PersisterInterface as Persister;

class UserStateManager implements UserStateManagerInterface
{
    /**
     * The user query repository
     * @var UserQuery
     */
    private $userQuery;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        UserQuery $userQuery,
        Persister $persister
    ) {
        $this->userQuery = $userQuery;
        $this->persister = $persister;
    }

    public function changeStatus(string $username, bool $newState)
    {
        if (Player::user()->isWritter()) {
            throw new UserNotAllowedException();
        }

        $user = $this->userQuery
            ->findByUsername($username);

        $newState ?
            $user->enable() :
            $user->disable();

        $this->persister->persist($user);
    }
}
