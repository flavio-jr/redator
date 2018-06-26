<?php

namespace App\Repositories\UserRepository\Destruction;

use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Services\Player;
use App\Exceptions\UserNotAllowedToRemoveUsers;

final class UserDestruction implements UserDestructionInterface
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

    public function destroy(string $username)
    {
        $loggedUser = Player::user();

        if (!$loggedUser->isMaster()) {
            throw new UserNotAllowedToRemoveUsers();    
        }

        $user = $this->userQuery
            ->findByUsername($username, false);

        if ($user->isMaster()) {
            throw new UserNotAllowedToRemoveUsers();
        }

        $this->persister->remove($user);
    }
}