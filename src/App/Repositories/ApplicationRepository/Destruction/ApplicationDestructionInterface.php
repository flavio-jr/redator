<?php

namespace App\Repositories\ApplicationRepository\Destruction;

interface ApplicationDestructionInterface
{
    /**
     * Destroy an application
     * @method destroy
     * @param string $appName
     * @return bool
     */
    public function destroy(string $appName): bool;
}