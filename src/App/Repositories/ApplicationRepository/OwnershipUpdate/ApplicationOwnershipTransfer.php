<?php

namespace App\Repositories\ApplicationRepository\OwnershipUpdate;

use App\Factorys\Application\Query\ApplicationQueryFactoryInterface;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Repositories\UserRepository\Query\UserQueryInterface as UserQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Entities\Application;
use App\Exceptions\UserNotAllowedReceiveApplicationOwnershipTransfer;

final class ApplicationOwnershipTransfer implements ApplicationOwnershipTransferInterface
{
    /**
     * The application query repository
     * @var ApplicationQueryFactoryInterface
     */
    private $applicationQueryFactory;

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
        ApplicationQueryFactoryInterface $applicationQueryFactory,
        UserQuery $userQuery,
        Persister $persister
    ) {
        $this->applicationQueryFactory = $applicationQueryFactory;
        $this->userQuery = $userQuery;
        $this->persister = $persister;
    }

    /**
     * @inheritdoc
     */
    public function transferOwnership(string $applicationName, string $username): Application
    {
        $newOwner = $this->userQuery
            ->findByUsername($username);

        if ($newOwner->isWritter()) {
            throw new UserNotAllowedReceiveApplicationOwnershipTransfer();
        }

        $application = $this->applicationQueryFactory
            ->getApplicationQuery()
            ->getApplication($applicationName);

        $application->setAppOwner($newOwner);

        $this->persister->persist($application);

        return $application;
    }
}