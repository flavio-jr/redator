<?php

namespace App\Repositories\PublicationRepository\Collect;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Querys\Publications\PublicationQueryInterface as PublicationQuery;
use App\Entities\Publication;

final class PublicationCollection implements PublicationCollectionInterface
{
    /**
     * The application query repository
     * @var ApplicationQuery
     */
    private $applicationQuery;

    /**
     * The publication query class
     * @var PublicationQuery
     */
    private $publicationQuery;

    public function __construct(
        ApplicationQuery $applicationQuery,
        PublicationQuery $publicationQuery
    ) {
        $this->applicationQuery = $applicationQuery;
        $this->publicationQuery = $publicationQuery;
    }

    public function get(string $applicationIdentifier, array $filters = []): array
    {
        $application = $this->applicationQuery
            ->getApplication($applicationIdentifier);

        if (!$application) {
            return [];
        }

        $publicationCollection = $this->publicationQuery
            ->get($application, $filters);
        
        return array_map(function (array $publication) {
            return array_diff_key($publication, [
                'title'       => true,
                'description' => true,
                'category'    => true,
                'createdAt'   => true
            ]);
        }, $publicationCollection);
    }
}