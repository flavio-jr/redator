<?php

namespace App\Filters;

use Slim\Http\Request;
use Doctrine\ORM\QueryBuilder;
use App\Repositories\PublicationRepository;
use DateTime;
use Carbon\Carbon;

class PublicationFilter implements FilterInterface
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

    /**
     * The min date of publication
     * @var DateTime
     */
    private $minDate;

    /**
     * The max date of publication
     * @var DateTime
     */
    private $maxDate;

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
    public function setFilters(array $requestParameters): FilterInterface
    {
        $this->page = $requestParameters['page'] ?? 0;
        $this->category = $requestParameters['category'] ?? null;
        $this->title = $requestParameters['title'] ?? '';
        $this->application = $requestParameters['application'] ?? null;
        $this->minDate = $requestParameters['min_date'] ?? null;
        $this->maxDate = $requestParameters['max_date'] ?? null;

        return $this;
    }

    /**
     * Filter the publications by title using like operation
     * @return self
     */
    public function filterByTitle()
    {
        if (empty($this->title)) return $this;

        $title = mb_strtolower($this->title);

        $this->publicationQueryBuilder
            ->andWhere('LOWER(' . self::QB_ALIAS . ".title) LIKE :title")
            ->setParameter('title', "%{$title}%");

        return $this;
    }

    /**
     * Filter the publications by category
     * @return self
     */
    public function filterByCategory()
    {
        if (!$this->category) return $this;

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
        if (!$this->application) return $this;

        $this->publicationQueryBuilder
            ->andWhere(self::QB_APPLICATION_ALIAS . ".id = :application")
            ->setParameter('application', "{$this->application}");

        return $this;
    }

    /**
     * Filter the publications for min date of creation
     * @return self
     */
    public function filterByMinDate()
    {
        if (!$this->minDate) return $this;

        $this->publicationQueryBuilder
            ->andWhere(self::QB_ALIAS . '.createdAt >= :minData')
            ->setParameter('minData', Carbon::createFromFormat('d/m/Y', $this->minDate));

        return $this;
    }

    /**
     * Filter the publications for max date of creation
     * @return self
     */
    public function filterByMaxDate()
    {
        if (!$this->maxDate) return $this;

        $this->publicationQueryBuilder
            ->andWhere(self::QB_ALIAS . '.createdAt <= :maxDate')
            ->setParameter('maxDate', Carbon::createFromFormat('d/m/Y', $this->maxDate));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get(): array
    {
        $pubPrefix = self::QB_ALIAS;
        $categoryPrefix = self::QB_CATEGORY_ALIAS;

        return $this->publicationQueryBuilder
            ->select("{$pubPrefix}.title, {$pubPrefix}.description, {$categoryPrefix}.name as category")
            ->setFirstResult($this->page)
            ->getQuery()
            ->setMaxResults(self::MAX_PER_PAGE)
            ->getResult();
    }
}