<?php

namespace App\Repositories\ApplicationTeamRepository\Destruction;

use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use App\Repositories\ApplicationRepository\Finder\ApplicationFinderInterface as ApplicationFinder;
use App\Services\Persister\PersisterInterface as Persister;
use App\Exceptions\EntityNotFoundException;
use App\Services\Player;
use App\Entities\User;
use App\Entities\Application;
use App\Exceptions\UserNotAllowedToRemoveMemberFromApplication;

class ApplicationMemberDestruction implements ApplicationMemberDestructionInterface
{
    /**
     * The user query repository
     * @var UserQuery
     */
    private $userQuery;

    /**
     * The application finder repository
     * @var ApplicationFinder
     */
    private $applicationFinder;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        UserQuery $userQuery,
        ApplicationFinder $applicationFinder,
        Persister $persister
    ) {
        $this->userQuery = $userQuery;
        $this->applicationFinder = $applicationFinder;
        $this->persister = $persister;
    }

    public function destroy(string $username, string $appName): Application
    {
        $user = $this->userQuery
            ->findByUsername($username);

        $application = $this->applicationFinder
            ->find($appName);
            
        if (!$user || !$application) {
            throw new EntityNotFoundException('User | Application');
        }

        if ($this->canLoggedUserRemoveMembership($user, $application)) {
            $application->removeUserOfTeam($user);

            $this->persister->persist($user);

            return $application;
        }

        throw new UserNotAllowedToRemoveMemberFromApplication();
    }

    private function canLoggedUserRemoveMembership(User $member, Application $application)
    {
        $loggedUser = Player::user();
        $loggedUserUsername = $loggedUser->getUsername();

        return $member->getUsername() === $loggedUserUsername ||
            $application->getAppOwner()->getUsername() === $loggedUserUsername ||
            $loggedUser->isMaster();
    }
}