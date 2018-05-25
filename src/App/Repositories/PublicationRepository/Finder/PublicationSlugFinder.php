<?php

namespace App\Repositories\PublicationRepository\Finder;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\Publication;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;

final class PublicationSlugFinder implements PublicationFinderInterface
{
    /**
     * The publication entity repository
     * @var EntityRepository
     */
    private $repository;

    /**
     * The application query repository
     * @var ApplicationQuery
     */
    private $applicationQuery;

    public function __construct(
        EntityManager $em,
        ApplicationQuery $applicationQuery
    ) {
        $this->repository = $em->getRepository(Publication::class);
        $this->applicationQuery = $applicationQuery;
    }

    public function find(string $identifier, string $applicationSlug): ?Publication
    {
        $application = $this->applicationQuery
            ->getApplication($applicationSlug);

        if (!$application) {
            return null;
        }

        return $this->repository
            ->findOneBy([
                'slug'           => $identifier,
                'application'    => $application->getId()
            ]);
    }
}