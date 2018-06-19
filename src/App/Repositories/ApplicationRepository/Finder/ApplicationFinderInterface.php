<?php

namespace App\Repositories\ApplicationRepository\Finder;

use App\Entities\Application;

interface ApplicationFinderInterface
{
    /**
     * Find an application by the given identifier
     * @method find
     * @param string $identifier
     * @return Application
     */
    public function find(string $identifier): Application;
}