<?php

namespace App\Repositories\ApplicationRepository\Destruction;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Factorys\Application\Query\ApplicationQueryFactoryInterface as ApplicationQueryFactory;

final class ApplicationDestruction implements ApplicationDestructionInterface
{
    /**
     * The repository for querying an application
     * @var ApplicationQueryFactory
     */
    private $applicationQueryFactory;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        ApplicationQueryFactory $applicationQueryFactory,
        Persister $persister
    ) {
        $this->applicationQueryFactory = $applicationQueryFactory;
        $this->persister = $persister;
    }

    public function destroy(string $appName): bool
    {
        $application = $this->applicationQueryFactory
            ->getApplicationQuery()
            ->getApplication($appName);

        if (!$application) return false;

        $this->persister->remove($application);

        return true;
    }
}