<?php

namespace App\Repositories\ApplicationRepository\OwnershipUpdate;

use App\Entities\Application;

interface ApplicationOwnershipTransferInterface
{
    /**
     * Transfers an application ownhership
     * @method transferOwnership
     * @param string $applicationName
     * @param string $username
     * @return Application
     */
    public function transferOwnership(string $applicationName, string $username): Application;
}