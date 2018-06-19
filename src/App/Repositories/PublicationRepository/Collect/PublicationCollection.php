<?php

namespace App\Repositories\PublicationRepository\Collect;

use App\Querys\Publications\PublicationQueryInterface as PublicationQuery;
use App\Entities\Publication;
use App\Factorys\Application\Query\ApplicationQueryFactoryInterface;

final class PublicationCollection implements PublicationCollectionInterface
{
    /**
     * The application query repository
     * @var ApplicationQueryFactoryInterface
     */
    private $applicationQueryFactory;

    /**
     * The publication query class
     * @var PublicationQuery
     */
    private $publicationQuery;

    public function __construct(
        ApplicationQueryFactoryInterface $applicationQueryFactory,
        PublicationQuery $publicationQuery
    ) {
        $this->applicationQueryFactory = $applicationQueryFactory;
        $this->publicationQuery = $publicationQuery;
    }

    public function get(string $applicationIdentifier, array $filters = []): array
    {
        $application = $this->applicationQueryFactory
            ->getApplicationQuery()
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