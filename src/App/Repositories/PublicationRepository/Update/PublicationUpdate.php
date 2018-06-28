<?php

namespace App\Repositories\PublicationRepository\Update;

use App\Repositories\PublicationRepository\Finder\PublicationFinderInterface as PublicationFinder;
use App\Repositories\CategoryRepository\Query\CategoryQueryInterface as CategoryQuery;
use App\Services\Persister\PersisterInterface as Persister;
use App\Services\HtmlSanitizer\HtmlSanitizerInterface as HtmlSanitizer;
use App\Services\Player;

final class PublicationUpdate implements PublicationUpdateInterface
{
    /**
     * The publication finder
     * @var PublicationFinder
     */
    private $publicationFinder;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    /**
     * The HTML Sanitizer service
     * @var HtmlSanitizer
     */
    private $htmlSanitizer;

    /**
     * The category query repository
     * @var CategoryQuery
     */
    private $categoryQuery;

    public function __construct(
        PublicationFinder $publicationFinder,
        Persister $persister,
        HtmlSanitizer $htmlSanitizer,
        CategoryQuery $categoryQuery
    ) {
        $this->publicationFinder = $publicationFinder;
        $this->persister = $persister;
        $this->htmlSanitizer = $htmlSanitizer;
        $this->categoryQuery = $categoryQuery;
    }

    /**
     * @inheritdoc
     */
    public function update(string $publicationSlug, string $applicationSlug, array $data): bool
    {
        if (Player::user()->isWritter()) {
            return false;
        }
        
        $publication = $this->publicationFinder
            ->find($publicationSlug, $applicationSlug);

        if (!$publication) {
            return false;
        }

        $data['application'] = $publication->getApplication();

        $data['category'] = $this->categoryQuery
            ->getCategoryByName($data['category']);

        if (!$data['category']) {
            return false;
        }

        $data['body'] = $this->htmlSanitizer->sanitize($data['body']);

        $publication->fromArray($data);

        $this->persister->persist($publication);

        return true;
    }
}