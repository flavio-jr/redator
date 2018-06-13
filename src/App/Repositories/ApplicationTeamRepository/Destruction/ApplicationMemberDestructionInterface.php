<?php

namespace App\Repositories\ApplicationTeamRepository\Destruction;

use App\Entities\Application;

interface ApplicationMemberDestructionInterface
{
    /**
     * Removes an user from the application team
     * @method destroy
     * @param string $username
     * @param string $appName
     * @return Application
     */
    public function destroy(string $username, string $appName): Application;
}