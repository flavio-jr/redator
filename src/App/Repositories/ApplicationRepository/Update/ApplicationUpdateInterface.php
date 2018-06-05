<?php

namespace App\Repositories\ApplicationRepository\Update;

interface ApplicationUpdateInterface
{
    /**
     * Updates an app data
     * @method update
     * @param string $appName The application name
     * @param array $data The app new data
     * @return bool The result of the update operation
     */
    public function update(string $appName, array $data): bool;
}