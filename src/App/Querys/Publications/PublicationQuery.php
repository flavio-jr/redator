<?php

namespace App\Querys\Publications;

use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\ORM\QueryBuilder;
use App\Entities\Application;
use Doctrine\ORM\EntityManager;
use App\Entities\Publication;
use Carbon\Carbon;

final class PublicationQuery implements PublicationQueryInterface
{
    const PUBLICATION_ALIAS = 'p';
    const CATEGORY_ALIAS = 'c';
    const FIRST_PAGE = 0;

    /**
     * The max number of returned publications
     * @var int
     */
    private $maxPerPage;

    /**
     * The query builder
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(
        int $maxPerPage,
        EntityManager $em
    ) {
        $this->maxPerPage = $maxPerPage;
        $this->queryBuilder = $em
            ->getRepository(Publication::class)
            ->createQueryBuilder(self::PUBLICATION_ALIAS)
            ->innerJoin(self::PUBLICATION_ALIAS . '.category', self::CATEGORY_ALIAS);
    }

    public function get(Application $application, array $filters = []): array
    {
        $this->queryBuilder
            ->where(self::PUBLICATION_ALIAS . '.application = :application')
            ->setParameter('application', $application->getId());

        if (count($filters) > 0) {
            $this
                ->filterStartDate($filters)
                ->filterEndDate($filters)
                ->filterTitle($filters)
                ->filterCategory($filters);
        }

        return $this->queryBuilder
            ->orderBy(self::PUBLICATION_ALIAS . '.createdAt', 'DESC')
            ->setFirstResult($this->queryParameters['page'] ?? self::FIRST_PAGE)
            ->getQuery()
            ->setMaxResults($this->maxPerPage)
            ->getArrayResult();
    }

    private function filterStartDate(array $filters): self
    {
        $start = Carbon::now()->subDays(Publication::DEFAULT_START_DATE);

        if (isset($filters['start_date'])) {
            $start = Carbon::createFromFormat('d/m/Y', $filters['start_date']);
        }

        $this->queryBuilder
            ->andWhere(self::PUBLICATION_ALIAS . '.createdAt >= :start')
            ->setParameter('start', $start);

        return $this;
    }

    private function filterEndDate(array $filters): self
    {
        $end = Carbon::now();

        if (isset($filters['end_date'])) {
            $end = Carbon::createFromFormat('d/m/Y', $filters['end_date']);
        }

        $this->queryBuilder
            ->andWhere(self::PUBLICATION_ALIAS . '.createdAt <= :end')
            ->setParameter('end', $end);

        return $this;
    }

    private function filterTitle(array $filters): self
    {
        if (!isset($filters['title']) || empty($filters['title'])) {
            return $this;
        }

        $title = mb_strtolower($filters['title']);

        return $this->queryBuilder
            ->andWhere('LOWER(' . self::PUBLICATION_ALIAS . '.title) LIKE :title')
            ->setParameter('title', $title);
    }

    public function filterCategory(array $filters): self
    {
        if (!isset($filters['category']) || empty($filters['category'])) {
            return $this;
        }

        return $this->queryBuilder
            ->andWhere(self::CATEGORY_ALIAS . '.slug = :category')
            ->setParameter('category', $filters['category']);
    }
}