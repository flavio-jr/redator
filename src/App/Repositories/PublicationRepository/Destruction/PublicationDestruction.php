<?php

namespace App\Repositories\PublicationRepository\Destruction;

use App\Repositories\PublicationRepository\Finder\PublicationFinderInterface as PublicationFinder;
use App\Services\Persister;

final class PublicationDestruction implements PublicationDestructionInterface
{
    /**
     * The publication finder repository
     * @var PublicationFinder
     */
    private $publicationFinder;

    /**
     * The persister service
     * @var Persister
     */
    private $persister;

    public function __construct(
        PublicationFinder $publicationFinder,
        Persister $persister
    )
    {
        $this->publicationFinder = $publicationFinder;
        $this->persister = $persister;
    }

    public function destroy(string $publicationSlug, string $applicationSlug): bool
    {
        $publication = $this->publicationFinder
            ->find($publicationSlug, $applicationSlug);

        if (!$publication) {
            return false;
        }

        $this->persister->remove($publication);

        return true;
    }
}