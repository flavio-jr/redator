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
}