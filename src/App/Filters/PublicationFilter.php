<?php

namespace App\Filters;

use Slim\Http\Request;
use Doctrine\ORM\QueryBuilder;
use App\Repositories\PublicationRepository;

class PublicationFilter
{
    /**
     * The alias for the query builder
     * @var string
     */
    private const QB_ALIAS = 'p';

    /**
     * The alias for the join with category
     * @var string
     */
    private const QB_CATEGORY_ALIAS = 'c';

    /**
     * The alias for the join with application
     * @var string
     */
    private const QB_APPLICATION_ALIAS = 'a';

    /**
     * Max number of registers per page
     * @var int
     */
    private const MAX_PER_PAGE = 15;

    /**
     * The repository for publication
     * @var QueryBuilder
     */
    private $publicationQueryBuilder;

    /**
     * The page to offset result
     * @var int
     */
    private $page = 0;

    /**
     * The category of the publication
     * @var string
     */
    private $category = null;

    /**
     * The title of the publication
     * @var string
     */
    private $title = '';

    /**
     * The application owner of the publication
     * @var string
     */
    private $application = null;

    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationQueryBuilder = $publicationRepository
            ->createQueryBuilder(self::QB_ALIAS)
            ->innerJoin(self::QB_ALIAS . '.category', self::QB_CATEGORY_ALIAS)
            ->innerJoin(self::QB_ALIAS . '.application', self::QB_APPLICATION_ALIAS);
    }

    /**
     * Set the values to filter
     * @param array $requestParameters
     * @return self
     */
    public function setFilters(array $requestParameters)
    {
        $this->page = $requestParameters['page'] ?? 0;
        $this->category = $requestParameters['category'] ?? null;
        $this->title = $requestParameters['title'] ?? '';
        $this->application = $requestParameters['application'] ?? null;

        return $this;
    }

    /**
     * Filter the publications by title using like operation
     * @return self
     */
    public function filterByTitle()
    {
        $this->publicationQueryBuilder
            ->andWhere(self::QB_ALIAS . ".title LIKE :title")
            ->setParameter('title', "%{$this->title}%");

        return $this;
    }

    /**
     * Filter the publications by category
     * @return self
     */
    public function filterByCategory()
    {
        $this->publicationQueryBuilder
            ->andWhere(self::QB_CATEGORY_ALIAS . ".id = :category")
            ->setParameter('category', "{$this->category}");

        return $this;
    }

    /**
     * Filter the publications by application
     * @return self
     */
    public function filterByApplication()
    {
        $this->publicationQueryBuilder
            ->andWhere(self::QB_APPLICATION_ALIAS . ".id = :application")
            ->setParameter('application', "{$this->application}");

        return $this;
    }

    public function get(): array
    {
        return $this->publicationQueryBuilder
            ->setFirstResult($this->page)
            ->getQuery()
            ->setMaxResults(self::MAX_PER_PAGE)
            ->getResult();
    }
}