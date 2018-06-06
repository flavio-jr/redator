<?php

namespace App\Repositories\UserMasterRepository\Store;

use App\Entities\User;
use App\Services\Persister\PersisterInterface as Persister;
use App\Repositories\UserMasterRepository\Query\UserMasterQueryInterface as UserMasterQuery;
use App\Exceptions\EntityNotFoundException;

final class UserMasterStore implements UserMasterStoreInterface
{
    /**
     * The user entity
     * @var User
     */
    private $user;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The user master query repository
     * @var UserMasterQuery
     */
    private $userMasterQuery;

    public function __construct(
        User $user,
        Persister $persister,
        UserMasterQuery $userMasterQuery
    ) {
        $this->user = $user;
        $this->persister = $persister;
        $this->userMasterQuery = $userMasterQuery;
    }

    public function store(): User
    {
        try {
            $user = $this->userMasterQuery->getMasterUser();

            return $user;
        } catch (EntityNotFoundException $e) {
            $userMaster = $this->user;
        
            $userMaster->setType('M');
            $userMaster->setName('Master');
            $userMaster->setUsername('master');
            $userMaster->setPassword(getenv('USER_DEFAULT_PASSWORD'));

            $this->persister->persist($userMaster);

            return $userMaster;
        }
    }
}