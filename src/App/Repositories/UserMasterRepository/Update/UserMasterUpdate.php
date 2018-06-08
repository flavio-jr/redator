<?php

namespace App\Repositories\UserMasterRepository\Update;

use App\Repositories\UserMasterRepository\Query\UserMasterQueryInterface as UserMasterQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Entities\User;

final class UserMasterUpdate implements UserMasterUpdateInterface
{
    /**
     * The User master query repository
     * @var UserMasterQuery
     */
    private $userMasterQuery;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        UserMasterQuery $userMasterQuery,
        Persister $persister
    ) {
        $this->userMasterQuery = $userMasterQuery;
        $this->persister = $persister;  
    }

    /**
     * @inheritdoc
     */
    public function update(): User
    {
        $masterUser = $this->userMasterQuery
            ->getMasterUser();

        $masterUser->setPassword(getenv('USER_DEFAULT_PASSWORD'));

        $this->persister->persist($masterUser);

        return $masterUser;
    }
}