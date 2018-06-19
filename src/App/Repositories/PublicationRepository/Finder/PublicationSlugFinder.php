<?php

namespace App\Repositories\PublicationRepository\Finder;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\Publication;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Factorys\Application\Query\ApplicationQueryFactoryInterface;

final class PublicationSlugFinder implements PublicationFinderInterface
{
    /**
     * The publication entity repository
     * @var EntityRepository
     */
    private $repository;

    /**
     * The application query repository
     * @var ApplicationQueryFactoryInterface
     */
    private $applicationQueryFactory;

    public function __construct(
        EntityManager $em,
        ApplicationQueryFactoryInterface $applicationQueryFactory
    ) {
        $this->repository = $em->getRepository(Publication::class);
        $this->applicationQueryFactory = $applicationQueryFactory;
    }

    public function find(string $identifier, string $applicationSlug): ?Publication
    {
        $application = $this->applicationQueryFactory
            ->getApplicationQuery()
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