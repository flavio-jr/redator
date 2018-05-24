<?php

namespace App\Repositories\PublicationRepository\Store;

use App\Entities\Publication;
use App\Services\Persister;
use App\Services\HtmlSanitizer;
use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface as ApplicationQuery;
use App\Repositories\CategoryRepository\Query\CategoryQueryInterface as CategoryQuery;

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
     * The repository for application query
     * @var ApplicationQuery
     */
    private $applicationQuery;

    /**
     * The repository for category query
     * @var CategoryQuery
     */
    private $categoryQuery;

    public function __construct(
        Publication $publication,
        Persister $persister,
        HtmlSanitizer $htmlSanitizer,
        ApplicationQuery $applicationQuery,
        CategoryQuery $categoryQuery
    )
    {
        $this->publication = $publication;
        $this->persister = $persister;
        $this->htmlSanitizer = $htmlSanitizer;
        $this->applicationQuery = $applicationQuery;
        $this->categoryQuery = $categoryQuery;
    }

    /**
     * @inheritdoc
     */
    public function store(string $application, array $data): ?Publication
    {
        $application = $this->applicationQuery->getApplication($application);
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