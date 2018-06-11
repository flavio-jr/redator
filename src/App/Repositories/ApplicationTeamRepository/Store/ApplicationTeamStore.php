<?php

namespace App\Repositories\ApplicationTeamRepository\Store;

use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Services\Player;
use App\Entities\Application;
use App\Exceptions\UserNotAllowedToAddMemberToApplication;

final class ApplicationTeamStore implements ApplicationTeamStoreInterface
{
    /**
     * The user query repository
     * @var UserQuery
     */
    private $userQuery;
    
    /**
     * The application query repository
     * @var ApplicationQuery
     */
    private $applicationQuery;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        UserQuery $userQuery,
        ApplicationQuery $applicationQuery,
        Persister $persister
    ) {
        $this->userQuery = $userQuery;
        $this->applicationQuery = $applicationQuery;
        $this->persister = $persister; 
    }

    public function store(string $memberUsername, string $appName): Application
    {
        $user = Player::user();

        if ($user->isWritter()) {
            throw new UserNotAllowedToAddMemberToApplication();
        }

        $member = $this->userQuery
            ->findByUsername($memberUsername);

        $application = $this->applicationQuery
            ->getApplication($appName);

        $ownerUsername = $application->getAppOwner()
            ->getUsername();

        if ($ownerUsername === $member->getUsername()) {
            throw new UserNotAllowedToAddMemberToApplication();
        }

        $application->addUserToTeam($member);

        $this->persister->persist($application);

        return $application;
    }
}