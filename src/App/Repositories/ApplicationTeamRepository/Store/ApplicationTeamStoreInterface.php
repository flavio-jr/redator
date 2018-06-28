<?php

namespace App\Repositories\ApplicationTeamRepository\Store;

use App\Entities\Application;

interface ApplicationTeamStoreInterface
{
    /**
     * Add member to application team
     * @method store
     * @param string $memberUsername
     * @param string $appName
     * @return Application
     */
    public function store(string $memberUsername, string $appName): Application;
}