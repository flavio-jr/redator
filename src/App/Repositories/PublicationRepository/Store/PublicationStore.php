<?php

namespace App\Repositories\PublicationRepository\Store;

use App\Entities\Publication;
use App\Services\Persister\PersisterInterface as Persister;
use App\Services\HtmlSanitizer\HtmlSanitizerInterface as HtmlSanitizer;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Repositories\CategoryRepository\Query\CategoryQueryInterface as CategoryQuery;
use App\Factorys\Application\Query\ApplicationQueryFactoryInterface;

final class PublicationStore implements PublicationStoreInterface
{
    /**
     * The publication entity
     * @var Publication
     */
    private $publication;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The HTML cleaner service
     * @var HtmlSanitizer
     */
    private $htmlSanitizer;

    /**
     * The factory to get the repository for application query
     * @var ApplicationQueryFactoryInterface
     */
    private $applicationQueryFactory;

    /**
     * The repository for category query
     * @var CategoryQuery
     */
    private $categoryQuery;

    public function __construct(
        Publication $publication,
        Persister $persister,
        HtmlSanitizer $htmlSanitizer,
        ApplicationQueryFactoryInterface $applicationQueryFactory,
        CategoryQuery $categoryQuery
    )
    {
        $this->publication = $publication;
        $this->persister = $persister;
        $this->htmlSanitizer = $htmlSanitizer;
        $this->applicationQueryFactory = $applicationQueryFactory;
        $this->categoryQuery = $categoryQuery;
    }

    /**
     * @inheritdoc
     */
    public function store(string $application, array $data): ?Publication
    {
        $application = $this->applicationQueryFactory
            ->getApplicationQuery()
            ->getApplication($application);

        $category = $this->categoryQuery->getCategoryByName($data['category']);

        if (!$application || !$category) {
            return null;
        }

        $data['application'] = $application;
        $data['category'] = $category;
        $data['body'] = $this->htmlSanitizer->sanitize($data['body']);

        $publication = $this->publication;
        $publication->fromArray($data);

        $this->persister->persist($publication);

        return $publication;
    }
}