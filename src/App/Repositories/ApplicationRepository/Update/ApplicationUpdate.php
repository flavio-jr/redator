<?php

namespace App\Repositories\ApplicationRepository\Update;

use App\Services\Persister;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Services\Player;

final class ApplicationUpdate implements ApplicationUpdateInterface
{
    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The repository for application
     * @var ApplicationQuery
     */
    private $applicationQuery;

    public function __construct(Persister $persister, ApplicationQuery $applicationQuery)
    {
        $this->persister = $persister;
        $this->applicationQuery = $applicationQuery;
    }

    /**
     * @inheritdoc
     */
    public function update(string $appName, array $data): bool
    {
        $application = $this->applicationQuery->getApplication($appName);

        if (!$application) return false;

        $data['owner'] = Player::user();

        $application->fromArray($data);

        $this->persister->persist($application);

        return true;
    }
}