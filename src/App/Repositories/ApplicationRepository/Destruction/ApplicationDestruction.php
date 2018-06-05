<?php

namespace App\Repositories\ApplicationRepository\Destruction;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Services\Persister\PersisterInterface as Persister;

final class ApplicationDestruction implements ApplicationDestructionInterface
{
    /**
     * The repository for querying an application
     * @var ApplicationQuery
     */
    private $applicationQuery;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        ApplicationQuery $applicationQuery,
        Persister $persister
    ) {
        $this->applicationQuery = $applicationQuery;
        $this->persister = $persister;
    }

    public function destroy(string $appName): bool
    {
        $application = $this->applicationQuery
            ->getApplication($appName);

        if (!$application) return false;

        $this->persister->remove($application);

        return true;
    }
}