<?php

namespace App\Repositories\ApplicationRepository\Update;

use App\Services\Persister;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Services\Player;
use App\Services\Slugify\SlugifyInterface as Slugifier;

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

    /**
     * The slugifier service
     * @var Slugifier
     */
    private $slugifier;

    public function __construct(
        Persister $persister,
        ApplicationQuery $applicationQuery,
        Slugifier $slugifier
    ) {
        $this->persister = $persister;
        $this->applicationQuery = $applicationQuery;
        $this->slugifier = $slugifier;
    }

    /**
     * @inheritdoc
     */
    public function update(string $appName, array $data): bool
    {
        $application = $this->applicationQuery->getApplication($appName);

        if (!$application) return false;

        $data['owner'] = Player::user();
        $data['slug'] = $this->slugifier->slugify($data['name']);

        $application->fromArray($data);

        $this->persister->persist($application);

        return true;
    }
}