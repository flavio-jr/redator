<?php

namespace App\Repositories\ApplicationRepository\Update;

use App\Services\Persister\PersisterInterface as Persister;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Services\Player;
use App\Factorys\Application\Query\ApplicationQueryFactoryInterface as ApplicationQueryFactory;

final class ApplicationUpdate implements ApplicationUpdateInterface
{
    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The repository for application
     * @var ApplicationQueryFactory
     */
    private $applicationQueryFactory;

    public function __construct(
        Persister $persister,
        ApplicationQueryFactory $applicationQueryFactory
    ) {
        $this->persister = $persister;
        $this->applicationQueryFactory = $applicationQueryFactory;
    }

    /**
     * @inheritdoc
     */
    public function update(string $appName, array $data): bool
    {
        $application = $this->applicationQueryFactory
            ->getApplicationQuery()
            ->getApplication($appName);

        if (!$application) return false;

        $data['owner'] = $application->getAppOwner();

        $application->fromArray($data);

        $this->persister->persist($application);

        return true;
    }
}