<?php

namespace App\Repositories\ApplicationRepository\Query;

use App\Entities\Application;

interface ApplicationQueryInterface
{
    /**
     * Search for an application by name
     * @method getApplication
     * @param string $appName
     * @return Application
     */
    public function getApplication(string $appName): ?Application;

    /**
     * Get the applications that belongs to the current user
     * @method getUserApplications
     * @return array
     */
    public function getUserApplications(): array;
}